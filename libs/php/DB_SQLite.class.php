<?php

    /**
     * DB_SQLite 类文件
     *
     * @desc SQLite 数据库类，SQLite 数据库驱动，包含一些常用方法
     *
     * @author  ShenKong <shenkong@openphp.cn>
     * @version $id
     * @package SPB Forum System
     * @copyright 您可以使用或者修改该文件，但是不得删除所有版权信息
     */

    define("DB_GETFETCH_ROW", SQLITE_NUM);
    define("DB_GETFETCH_ASSOC", SQLITE_ASSOC);
    define("DB_GETFETCH_ARRAY", SQLITE_BOTH);

    class DB_SQLite extends DB
    {
        /**
         * 数据库连接号
         *
         * @var     string
         * @access  public
         */
        var $conn   = null;

        /**
         * 当前数据库查询语句
         *
         * @var     string
         * @access  public
         */
        var $query  = null;

        /**
         * 数据库查询结果
         *
         * @var     string
         * @access  public
         */
        var $result = null;

        /**
         * 错误字符串
         *
         * @var     string
         * @access  public
         */
        var $errStr = null;

        /**
         * 快速查询
         *
         * @var     string
         * @access  public
         */
        var $quick  = false;

        /**
         * 数据库查询语句数组珼ebug 用
         *
         * @var     string
         * @access  public
         */
        var $sqlStr = array();

        function DB_SQLite($fetchMode)
        {
            if(!defined("DB_FETCH_MODE"))
            {
                switch ($fetchMode)
                {
                    case 3:
                        define("DB_FETCH_MODE", DB_GETFETCH_ROW);
                    break;
                    case 2:
                        define("DB_FETCH_MODE", DB_GETFETCH_ARRAY);
                    break;
                    default:
                        define("DB_FETCH_MODE", DB_GETFETCH_ASSOC);
                }
            }
        }

        function connect($dsn, $pConn = false)
        {
            $this->dbPath = $dsn["dbPath"];
            $this->dbName = $dsn["dbName"];
            $this->dbMode = $dsn["dbMode"];

            $connFunc = $pConn ? "sqlite_popen" : "sqlite_open";
            $this->conn = @ $connFunc($this->dbPath . $this->dbName, $this->dbMode, $error);
            if (!$this->conn)
            {
                $this->errStr = "DataBase Connect False : ($this->dbPath, $this->dbName) !<br />$error";
                $this->dbError();
            }
        }

        function selectDB()
        {
            return;
        }

        function query($query, $quick = false)
        {
            $this->quick = $quick;
            $this->query = $query;
            $this->sqlStr[] = $this->query;
            $queryFunc = $this->quick ? "sqlite_unbuffered_query" : "sqlite_query";
            $this->result = @ $queryFunc($this->query, $this->conn);
            $this->sQueries++;
            if (!$this->result)
            {
                $this->dbError();
            }
            return $this->result;
        }

        function close()
        {
            @ sqlite_close($this->conn);
        }

        function getOne($query)
        {
            if (!stristr($query, "limit"))
            {
                $query .= " Limit 1";
            }
            $this->query($query, true);
            $row = $this->fetchRow(DB_FETCH_ROW);
            $this->free();
            return $row[0];
        }

        function getRow($query, $fetchMode = DB_FETCH_MODE)
        {
            if (!stristr($query, "limit"))
            {
                $query .= " Limit 1";
            }
            $this->query($query, true);
            $row = $this->fetchRow($fetchMode);
            $this->free();
            return $row;
        }

        function getAll($query, $fetchMode = DB_FETCH_MODE)
        {
            $this->query($query, true);
            $allRows = array();
            while($rows = @ $this->fetchRow($fetchMode))
            {
                $allRows[] = $rows;
            }
            $this->free();
            return $allRows;
        }

        function fetchRow($fetchMode = DB_FETCH_MODE)
        {
            switch ($fetchMode)
            {
                case 3:
                    $fetchMode = DB_GETFETCH_ROW;
                break;
                case 2:
                    $fetchMode = DB_GETFETCH_ARRAY;
                break;
                case 1:
                    $fetchMode = DB_GETFETCH_ASSOC;
                break;
                default:
                    $fetchMode = DB_FETCH_MODE;
            }
            $rows = @ sqlite_fetch_array($this->result, $fetchMode);
            return $rows;
        }

        function update($query)
        {
            $this->query = $query;
            $this->sqlStr[] = $this->query;
            $this->result = sqlite_unbuffered_query($query, $this->conn);
            if (!$this->result)
            {
                $this->errStr = "Update data Error !";
                $this->dbError();
            }
            $this->uQueries++;
            $this->free();
            return true;
        }

        function simpleQuery($query, $from = 0, $limit = 0)
        {
            if($from)
            {
                $from = $from . ',';
            }
            if($limit)
            {
                $query .= ' Limit ' . $from. $limit;
            }
            $this->query($query);
        }

        function fields()
        {
            return @ sqlite_num_fields($this->result);
        }

        function fieldNames()
        {
            $fieldNames = array();
            $fields = $this->fields();
            for ($i = 0; $i < $fields; $i++)
            {
                $fieldNames[] = sqlite_field_name($this->result, $i);
            }
            return $fieldNames;
        }

        function free()
        {
            unset($this->result);
        }
    }

?>
