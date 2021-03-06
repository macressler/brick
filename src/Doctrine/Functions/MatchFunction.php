<?php

namespace Brick\Doctrine\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\Parser;

/**
 * Match(field, value) function for MySQL FULLTEXT search.
 */
class MatchFunction extends FunctionNode
{
    /**
     * @var \Doctrine\ORM\Query\AST\Node
     */
    private $field;

    /**
     * @var \Doctrine\ORM\Query\AST\Node
     */
    private $value;

    /**
     * {@inheritdoc}
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        return sprintf(
            'MATCH (%s) AGAINST (%s)',
            $this->field->dispatch($sqlWalker),
            $this->value->dispatch($sqlWalker)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->field = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->value = $parser->StringPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
