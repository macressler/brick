<?php

namespace Brick\Controller\Annotation;

use Brick\Http\Request;

/**
 * This annotation requires the RequestParamListener to be registered with the application.
 *
 * @Annotation
 * @Target("METHOD")
 */
class QueryParam extends RequestParam
{
    /**
     * {@inheritdoc}
     */
    public function getParameterType()
    {
        return 'query';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestParameters(Request $request)
    {
        return $request->getQuery();
    }
}
