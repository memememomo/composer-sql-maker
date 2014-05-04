<?php

use uchiko\SQL\Maker;

class SelectTest extends PHPUnit_Framework_TestCase {


    // driver sqlite
    public function testDriverSqliteColumnsAndTables() {
        $builder = new Maker(array('driver' => 'sqlite'));
        list($sql, $binds) = $builder->select('foo', array('*'));
        $this->assertEquals("SELECT *\nFROM \"foo\"", $sql);
        $this->assertEquals('', implode(',', $binds));
    }

    public function testDriverSqliteColumnsAndTablesWhereCauseHash() {
        $builder = new Maker(array('driver' => 'sqlite'));

        $table = 'foo';

        $fields = array('foo', 'bar');

        $where = array();
        $where['bar']  = 'baz';
        $where['john'] = 'man';

        list($sql, $binds) = $builder->select($table, $fields, $where);
        $this->assertEquals("SELECT \"foo\", \"bar\"\nFROM \"foo\"\nWHERE (\"bar\" = ?) AND (\"john\" = ?)", $sql);
        $this->assertEquals("baz,man", implode(',', $binds));
    }

    public function testDriverSqliteColumnsAndTablesWhereCauseArray() {
        $builder = new Maker(array('driver' => 'sqlite'));

        $table = 'foo';

        $fields = array('foo', 'bar');

        $where = array();
        $where[] = array('bar'  => 'baz');
        $where[] = array('john' => 'man');

        list($sql, $binds) = $builder->select($table, $fields, $where);
        $this->assertEquals("SELECT \"foo\", \"bar\"\nFROM \"foo\"\nWHERE (\"bar\" = ?) AND (\"john\" = ?)", $sql);
        $this->assertEquals("baz,man", implode(',', $binds));
    }

    public function testDriverSqliteColumnsAndTablesWhereCauseHashOrderBy() {
        $builder = new Maker(array('driver' => 'sqlite'));

        $table = 'foo';

        $fields = array('foo', 'bar');

        $where = array();
        $where['bar']  = 'baz';
        $where['john'] = 'man';

        $opt = array();
        $opt['order_by'] = 'yo';

        list($sql, $binds) = $builder->select($table, $fields, $where, $opt);
        $this->assertEquals("SELECT \"foo\", \"bar\"\nFROM \"foo\"\nWHERE (\"bar\" = ?) AND (\"john\" = ?)\nORDER BY yo", $sql);
        $this->assertEquals("baz,man", implode(',', $binds));
    }

    public function testDriverSqliteColumnsAndTablesWhereCauseArrayOrderBy() {
        $builder = new Maker(array('driver' => 'sqlite'));

        $table = 'foo';

        $fields = array('foo', 'bar');

        $where = array();
        $where[] = array('bar'  => 'baz');
        $where[] = array('john' => 'man');

        $opt = array();
        $opt['order_by'] = 'yo';

        list($sql, $binds) = $builder->select($table, $fields, $where, $opt);
        $this->assertEquals("SELECT \"foo\", \"bar\"\nFROM \"foo\"\nWHERE (\"bar\" = ?) AND (\"john\" = ?)\nORDER BY yo", $sql);
        $this->assertEquals("baz,man", implode(',', $binds));
    }


    public function testDriverSqliteColumnsAndTablesWhereCauseArrayOrderByLimitOffset() {
        $builder = new Maker(array('driver' => 'sqlite'));

        $table = 'foo';

        $fields = array('foo', 'bar');

        $where = array();
        $where[] = array('bar'  => 'baz');
        $where[] = array('john' => 'man');

        $opt = array();
        $opt['order_by'] = 'yo';
        $opt['limit'] = 1;
        $opt['offset'] = 3;

        list($sql, $binds) = $builder->select($table, $fields, $where, $opt);
        $this->assertEquals("SELECT \"foo\", \"bar\"\nFROM \"foo\"\nWHERE (\"bar\" = ?) AND (\"john\" = ?)\nORDER BY yo\nLIMIT 1 OFFSET 3", $sql);
        $this->assertEquals('baz,man', implode(',', $binds));
    }


    public function testDriverSqliteModifyPrefix() {
        $builder = new Maker(array('driver' => 'sqlite'));

        $table = 'foo';

        $fields = array('foo', 'bar');

        $where = array();

        $opt = array();
        $opt['prefix'] = 'SELECT SQL_CALC_FOUND_ROWS ';

        list($sql, $binds) = $builder->select($table, $fields, $where, $opt);

        $this->assertEquals("SELECT SQL_CALC_FOUND_ROWS \"foo\", \"bar\"\nFROM \"foo\"", $sql);
        $this->assertEquals('', implode(',', $binds));
    }

    public function testDriverSqliteOrderByScalar() {
        $builder = new Maker(array('driver' => 'sqlite'));
        list($sql, $binds) = $builder->select('foo', array('*'), array(), array('order_by' => 'yo'));
        $this->assertEquals("SELECT *\nFROM \"foo\"\nORDER BY yo", $sql);
        $this->assertEquals('', implode(',', $binds));
    }

    public function testDriverSqliteOrderByHash() {
        $builder = new Maker(array('driver' => 'sqlite'));

        $opt = array();
        $opt['order_by'] = array();
        $opt['order_by'][] = array('yo' => 'DESC');

        list($sql, $binds) = $builder->select('foo', array('*'), array(), $opt);
        $this->assertEquals("SELECT *\nFROM \"foo\"\nORDER BY \"yo\" DESC", $sql);
        $this->assertEquals('', implode(',', $binds));
    }

    public function testDriverSqliteOrderByArray() {
        $builder = new Maker(array('driver' => 'sqlite'));
        list($sql, $binds) = $builder->select('foo', array('*'), array(), array('order_by' => array('yo', 'ya')));
        $this->assertEquals("SELECT *\nFROM \"foo\"\nORDER BY yo, ya", $sql);
        $this->assertEquals('', implode(',', $binds));
    }

    public function testDriverSqliteOrderByMixed() {
        $builder = new Maker(array('driver' => 'sqlite'));
        list($sql, $binds) = $builder->select('foo', array('*'), array(), array('order_by' => array(array('yo' => 'DESC'), 'ya')));
        $this->assertEquals("SELECT *\nFROM \"foo\"\nORDER BY \"yo\" DESC, ya", $sql);
        $this->assertEquals('', implode(',', $binds));
    }

    public function testDriverSqliteFromMultiFrom() {
        $builder = new Maker(array('driver' => 'sqlite'));
        list($sql, $binds) = $builder->select(array('foo', 'bar'), array('*'), array());
        $this->assertEquals("SELECT *\nFROM \"foo\", \"bar\"", $sql);
        $this->assertEquals('', implode(',', $binds));
    }

    public function testDriverSqliteFromMultiFromWithAlias() {
        $builder = new Maker(array('driver' => 'sqlite'));
        list($sql, $binds) = $builder->select(array(array('foo' => 'f'), array('bar' => 'b')), array('*'), array());
        $this->assertEquals("SELECT *\nFROM \"foo\" \"f\", \"bar\" \"b\"", $sql);
        $this->assertEquals('', implode(',', $binds));
    }



    // driver mysql
    public function testDriverMysqlColumnsAndTables() {
        $builder = new Maker(array('driver' => 'mysql'));
        list($sql, $binds) = $builder->select( 'foo', array( '*' ) );
        $this->assertEquals("SELECT *\nFROM `foo`", $sql);
        $this->assertEquals('', implode(',', $binds));
    }

    public function testDriverMysqlColumnsAndTablesWhereCauseHash() {
        $builder = new Maker(array('driver' => 'mysql'));

        $table = 'foo';
        $columns = array('foo', 'bar');

        $where = array();
        $where['bar']  = 'baz';
        $where['john'] = 'man';

        list($sql, $binds) = $builder->select( $table, $columns, $where );
        $this->assertEquals("SELECT `foo`, `bar`\nFROM `foo`\nWHERE (`bar` = ?) AND (`john` = ?)", $sql);
        $this->assertEquals("baz,man", implode(',', $binds));
    }


    public function testDriverMysqlColumnsAndTablesWhereCauseArray() {
        $builder = new Maker(array('driver' => 'mysql'));

        $table = 'foo';
        $columns = array('foo', 'bar');

        $where = array();
        $where[] = array('bar'  => 'baz');
        $where[] = array('john' => 'man');

        list($sql, $binds) = $builder->select( $table, $columns, $where );
        $this->assertEquals("SELECT `foo`, `bar`\nFROM `foo`\nWHERE (`bar` = ?) AND (`john` = ?)", $sql);
        $this->assertEquals('baz,man', implode(',', $binds));
    }


    public function testDriverMysqlColumnsAndTablesWhereCauseHashOrderBy() {
        $builder = new Maker(array('driver' => 'mysql'));

        $table = 'foo';
        $columns = array('foo', 'bar');

        $where = array();
        $where['bar']  = 'baz';
        $where['john'] = 'man';

        $opt = array();
        $opt['order_by'] = 'yo';

        list($sql, $binds) = $builder->select( $table, $columns, $where, $opt );
        $this->assertEquals("SELECT `foo`, `bar`\nFROM `foo`\nWHERE (`bar` = ?) AND (`john` = ?)\nORDER BY yo", $sql);
        $this->assertEquals('baz,man', implode(',', $binds));
    }


    public function testDriverMysqlColumnsAndTablesWhereCauseArrayOrderBy() {
        $builder = new Maker(array('driver' => 'mysql'));

        $table = 'foo';
        $columns = array('foo', 'bar');

        $where = array();
        $where[] = array('bar'  => 'baz');
        $where[] = array('john' => 'man');

        $opt = array();
        $opt['order_by'] = 'yo';

        list($sql, $binds) = $builder->select( $table, $columns, $where, $opt );
        $this->assertEquals("SELECT `foo`, `bar`\nFROM `foo`\nWHERE (`bar` = ?) AND (`john` = ?)\nORDER BY yo", $sql);
        $this->assertEquals('baz,man', implode(',', $binds));
    }


    public function testDriverMysqlColumnsAndTablesWhereCauseArrayOrderByLimitOffset() {
        $builder = new Maker(array('driver' => 'mysql'));

        $table = 'foo';
        $columns = array('foo', 'bar');

        $where = array();
        $where[] = array('bar'  => 'baz');
        $where[] = array('john' => 'man');

        $opt = array();
        $opt['order_by'] = 'yo';
        $opt['limit'] = 1;
        $opt['offset'] = 3;

        list($sql, $binds) = $builder->select( $table, $columns, $where, $opt );
        $this->assertEquals("SELECT `foo`, `bar`\nFROM `foo`\nWHERE (`bar` = ?) AND (`john` = ?)\nORDER BY yo\nLIMIT 1 OFFSET 3", $sql);
        $this->assertEquals('baz,man', implode(',', $binds));
    }

    public function testDriverMysqlModifyPrefix() {
        $builder = new Maker(array('driver' => 'mysql'));

        $table = 'foo';
        $columns = array('foo', 'bar');

        $opt['prefix'] = 'SELECT SQL_CALC_FOUND_ROWS ';

        list($sql, $binds) = $builder->select( $table, $columns, array(), $opt );
        $this->assertEquals("SELECT SQL_CALC_FOUND_ROWS `foo`, `bar`\nFROM `foo`", $sql);
        $this->assertEquals('', implode(',', $binds));
    }

    public function testDriverMysqlOrderbyScalar() {
        $builder = new Maker(array('driver' => 'mysql'));

        $table = 'foo';
        $columns = array('*');

        $where = array();

        $opt = array();
        $opt['order_by'] = 'yo';

        list($sql, $binds) = $builder->select($table, $columns, $where, $opt);
        $this->assertEquals("SELECT *\nFROM `foo`\nORDER BY yo", $sql);
        $this->assertEquals('', implode(',', $binds));
    }

    public function testDriverMysqlOrderbyHash() {
        $builder = new Maker(array('driver' => 'mysql'));

        $table = 'foo';
        $columns = array('*');

        $where = array();

        $opt = array();
        $opt['order_by'] = array('yo' => 'DESC');

        list($sql, $binds) = $builder->select($table, $columns, $where, $opt);
        $this->assertEquals("SELECT *\nFROM `foo`\nORDER BY `yo` DESC", $sql);
        $this->assertEquals('', implode(',', $binds));
    }

    public function testDriverMysqlOrderbyArray() {
        $builder = new Maker(array('driver' => 'mysql'));

        $table = 'foo';
        $columns = array('*');

        $where = array();

        $opt = array();
        $opt['order_by'] = array('yo', 'ya');

        list($sql, $binds) = $builder->select($table, $columns, $where, $opt);
        $this->assertEquals("SELECT *\nFROM `foo`\nORDER BY yo, ya", $sql);
        $this->assertEquals('', implode(',', $binds));
    }

    public function testDriverMysqlOrderbyMixed() {
        $builder = new Maker(array('driver' => 'mysql'));

        $table = 'foo';
        $columns = array('*');

        $where = array();

        $opt = array();
        $opt['order_by'] = array(array('yo' => 'DESC'), 'ya');

        list($sql, $binds) = $builder->select($table, $columns, $where, $opt);
        $this->assertEquals("SELECT *\nFROM `foo`\nORDER BY `yo` DESC, ya", $sql);
        $this->assertEquals('', implode(',', $binds));
    }

    public function testDriverMysqlFromMultiFrom() {
        $builder = new Maker(array('driver' => 'mysql'));

        list($sql, $binds) = $builder->select(array('foo', 'bar'), array('*'));
        $this->assertEquals("SELECT *\nFROM `foo`, `bar`", $sql);
        $this->assertEquals('', implode(',', $binds));
    }

    public function testDriverMysqlFromMultiFromWithAlias() {
        $builder = new Maker(array('driver' => 'mysql'));

        list($sql, $binds) = $builder->select(array(array( 'foo' => 'f' ), array( 'bar' => 'b' )), array('*'));
        $this->assertEquals("SELECT *\nFROM `foo` `f`, `bar` `b`", $sql);
        $this->assertEquals('', implode(',', $binds));
    }



    // driver: mysql, quote_char: "", new_line: " "
    public function testDriverMysqlQuoteCharNewLineColumnsAndTables() {
        $builder = new Maker(array('driver' => 'mysql', 'quote_char' => '', 'new_line' => ' '));

        list($sql, $binds) = $builder->select('foo', array('*'));
        $this->assertEquals("SELECT * FROM foo", $sql);
        $this->assertEquals('', implode(',', $binds));
    }

    public function testDriverMysqlQuoteCharNewLineColumnsAndTablesWhereCauseHash() {
        $builder = new Maker(array('driver' => 'mysql', 'quote_char' => '', 'new_line' => ' '));

        $table = 'foo';
        $fields = array('foo', 'bar');

        $where = array();
        $where['bar']  = 'baz';
        $where['john'] = 'man';

        list($sql, $binds) = $builder->select($table, $fields, $where);
        $this->assertEquals("SELECT foo, bar FROM foo WHERE (bar = ?) AND (john = ?)", $sql);
        $this->assertEquals('baz,man', implode(',', $binds));
    }


    public function testDriverMysqlQuoteCharNewLineColumnsAndTablesWhereCauseArray() {
        $builder = new Maker(array('driver' => 'mysql', 'quote_char' => '', 'new_line' => ' '));

        $table = 'foo';
        $fields = array('foo', 'bar');

        $where = array();
        $where[] = array('bar', 'baz');
        $where[] = array('john', 'man');

        list($sql, $binds) = $builder->select($table, $fields, $where);
        $this->assertEquals("SELECT foo, bar FROM foo WHERE (bar = ?) AND (john = ?)", $sql);
        $this->assertEquals('baz,man', implode(',', $binds));
    }

    public function testDriverMysqlQuoteCharNewLineColumnsAndTablesWhereCauseHashOrderBy() {
        $builder = new Maker(array('driver' => 'mysql', 'quote_char' => '', 'new_line' => ' '));

        $table = 'foo';
        $fields = array('foo', 'bar');

        $where = array();
        $where['bar']  = 'baz';
        $where['john'] = 'man';

        $opt = array();
        $opt['order_by'] = 'yo';

        list($sql, $binds) = $builder->select($table, $fields, $where, $opt);
        $this->assertEquals("SELECT foo, bar FROM foo WHERE (bar = ?) AND (john = ?) ORDER BY yo", $sql);
        $this->assertEquals('baz,man', implode(',', $binds));
    }


    public function testDriverMysqlQuoteCharNewLineColumnsAndTablesWhereCauseArrayOrderBy() {
        $builder = new Maker(array('driver' => 'mysql', 'quote_char' => '', 'new_line' => ' '));

        $table = 'foo';
        $fields = array('foo', 'bar');

        $where = array();
        $where[] = array('bar', 'baz');
        $where[] = array('john', 'man');

        $opt = array();
        $opt['order_by'] = 'yo';

        list($sql, $binds) = $builder->select($table, $fields, $where, $opt);
        $this->assertEquals("SELECT foo, bar FROM foo WHERE (bar = ?) AND (john = ?) ORDER BY yo", $sql);
        $this->assertEquals('baz,man', implode(',', $binds));
    }

    public function testDriverMysqlQuoteCharNewLineModifyPrefix() {
        $builder = new Maker(array('driver' => 'mysql', 'quote_char' => '', 'new_line' => ' '));

        $table = 'foo';
        $fields = array('foo', 'bar');

        $where = array();

        $opt = array();
        $opt['prefix'] = 'SELECT SQL_CALC_FOUND_ROWS ';

        list($sql, $binds) = $builder->select($table, $fields, $where, $opt);
        $this->assertEquals("SELECT SQL_CALC_FOUND_ROWS foo, bar FROM foo", $sql);
        $this->assertEquals('', implode(',', $binds));
    }

    public function testDriverMysqlQuoteCharNewLineOrderByScalar() {
        $builder = new Maker(array('driver' => 'mysql', 'quote_char' => '', 'new_line' => ' '));

        $table = 'foo';
        $fields = array('*');

        $where = array();

        $opt = array();
        $opt['order_by'] = 'yo';

        list($sql, $binds) = $builder->select($table, $fields, $where, $opt);
        $this->assertEquals("SELECT * FROM foo ORDER BY yo", $sql);
        $this->assertEquals('', implode(',', $binds));
    }

    public function testDriverMysqlQuoteCharNewLineOrderByHash() {
        $builder = new Maker(array('driver' => 'mysql', 'quote_char' => '', 'new_line' => ' '));

        $table = 'foo';
        $fields = array('*');

        $where = array();

        $opt = array();
        $opt['order_by'] = array('yo' => 'DESC');

        list($sql, $binds) = $builder->select($table, $fields, $where, $opt);
        $this->assertEquals("SELECT * FROM foo ORDER BY yo DESC", $sql);
        $this->assertEquals('', implode(',', $binds));
    }

    public function testDriverMysqlQuoteCharNewLineOrderByArray() {
        $builder = new Maker(array('driver' => 'mysql', 'quote_char' => '', 'new_line' => ' '));

        $table = 'foo';
        $fields = array('*');

        $where = array();

        $opt = array();
        $opt['order_by'] = array('yo', 'ya');

        list($sql, $binds) = $builder->select($table, $fields, $where, $opt);
        $this->assertEquals("SELECT * FROM foo ORDER BY yo, ya", $sql);
        $this->assertEquals('', implode(',', $binds));
    }

    public function testDriverMysqlQuoteCharNewLineOrderByMixed() {
        $builder = new Maker(array('driver' => 'mysql', 'quote_char' => '', 'new_line' => ' '));

        $table = 'foo';
        $fields = array('*');

        $where = array();

        $opt = array();
        $opt['order_by'] = array(array('yo' => 'DESC'), 'ya');

        list($sql, $binds) = $builder->select($table, $fields, $where, $opt);
        $this->assertEquals("SELECT * FROM foo ORDER BY yo DESC, ya", $sql);
        $this->assertEquals('', implode(',', $binds));
    }

    public function testDriverMysqlQuoteCharNewLineFromMultiFrom() {
        $builder = new Maker(array('driver' => 'mysql', 'quote_char' => '', 'new_line' => ' '));

        list($sql, $binds) = $builder->select(array('foo', 'bar'), array('*'));
        $this->assertEquals("SELECT * FROM foo, bar", $sql);
        $this->assertEquals('', implode(',', $binds));
    }

    public function testDriverMysqlQuoteCharNewLineFromMultiFromWithAlias() {
        $builder = new Maker(array('driver' => 'mysql', 'quote_char' => '', 'new_line' => ' '));

        list($sql, $binds) = $builder->select(array(array('foo' => 'f'), array('bar' => 'b')), array('*'));
        $this->assertEquals("SELECT * FROM foo f, bar b", $sql);
        $this->assertEquals('', implode(',', $binds));
    }

}
