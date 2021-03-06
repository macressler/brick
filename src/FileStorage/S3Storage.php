<?php

namespace Brick\FileStorage;

use Aws\Common\Exception\AwsExceptionInterface;
use Aws\S3\Exception\NoSuchKeyException;
use Aws\S3\S3Client;

/**
 * Amazon S3 implementation of the Storage interface.
 */
class S3Storage implements Storage
{
    /**
     * @var \Aws\S3\S3Client
     */
    private $s3;

    /**
     * @var string
     */
    private $bucket;

    /**
     * Class constructor.
     *
     * @param \Aws\S3\S3Client $s3     The S3 client.
     * @param string           $bucket The bucket name.
     */
    public function __construct(S3Client $s3, $bucket)
    {
        $this->s3 = $s3;
        $this->bucket = $bucket;
    }

    /**
     * {@inheritdoc}
     */
    public function put($path, $data)
    {
        try {
            $this->s3->putObject([
                'Bucket' => $this->bucket,
                'Key'    => $path,
                'Body'   => $data
            ]);
        } catch (AwsExceptionInterface $e) {
            throw Exception\StorageException::putError($path, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($path)
    {
        try {
            $model = $this->s3->getObject([
                'Bucket' => $this->bucket,
                'Key'    => $path
            ]);

            return (string) $model->get('Body');
        } catch (NoSuchKeyException $e) {
            throw Exception\NotFoundException::pathNotFound($path, $e);
        } catch (AwsExceptionInterface $e) {
            throw Exception\StorageException::getError($path, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete($path)
    {
        try {
            $this->s3->deleteObject([
                'Bucket' => $this->bucket,
                'Key'    => $path
            ]);
        } catch (AwsExceptionInterface $e) {
            throw Exception\StorageException::deleteError($path, $e);
        }
    }
}
