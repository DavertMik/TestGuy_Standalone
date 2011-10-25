<?php
/**
 * Author: davert
 * Date: 14.10.11
 *
 * Class PDOFixtures
 * Description:
 * 
 */
 
class TestGuy_Module_DbPopulator extends TestGuy_Module {

    protected $sql;
    protected $dbh;
    

    protected $requiredFields = array('dump', 'dsn', 'user', 'password');

    public function _initialize() {

        if (!file_exists($this->config['dump'])) {
            throw new TestGuy_Exception_ModuleConfig(__CLASS__, "
                File with dump deesn't exist.\n
                Please, check path for sql file: ".$this->config['dump']);
        }

        $sql = file_get_contents($this->config['dump']);
        $this->sql = $sql;

        try {
            $dbh = new PDO($this->config['dsn'], $this->config['user'], $this->config['password']);
            $this->dbh = $dbh;
        } catch (PDOException $e) {
            throw new TestGuy_Exception_Module(__CLASS__, $e->getMessage());
        }
    }

    public function _before() {

        $dbh = $this->dbh;
        try {
            $res = $dbh->query('show tables')->fetchAll();
            foreach ($res as $row) {
                $dbh->exec('drop table '.$row[0]);
            }
            $this->queries = explode(';', $this->sql);

            foreach ($this->queries as $query) {
                $dbh->exec($query);
            }

        } catch (PDOException $e) {
            throw new TestGuy_Exception_Module(__CLASS__, $e->getMessage());
        }
    }

}
