<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mongo_DB extends MongoClient
{

    public function __construct()
    {
        $ci = &get_instance();

        $ci->load->config('mongo_db');

        $server = $ci->config->item('mongo_server');
        $username = $ci->config->item('mongo_username');
        $password = $ci->config->item('mongo_password');
        $database = $ci->config->item('mongo_database');

        try
        {
            if (strcasecmp($username, '') !== 0 && strcasecmp($password, '') !== 0)
            {
                $connectionString = "mongodb://$username:$password@$server/$database";
            }
            else
            {
                $connectionString = "mongodb://$server/$database";
            }

            parent::__construct($connectionString);
            $this->db = $this->$database;
        }
        catch (MongoConnectionException $ex)
        {
            $error = &load_class('Exceptions', 'core');
            exit($error->show_error('MongoDB Connection Error', 'A MongoDB error occured while trying to connect to the database!', 'error_db'));
        }
        catch (Exception $ex)
        {
            $error = &load_class('Exceptions', 'core');
            exit($error->show_error('MongoDB Error', $ex->getMessage(), 'error_db'));
        }
    }

}