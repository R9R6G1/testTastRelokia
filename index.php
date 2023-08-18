<?php

class ZendeskAPI
{
    private $url;
    private $username;
    private $password;

    public function __construct($url, $username, $password)
    {
        $this->url = $url;
        $this->username = $username;
        $this->password = $password;
    }

    public function fetchData()
    {
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Basic ' . base64_encode($this->username . ':' . $this->password)
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}

class CSVWriter
{
    private $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function writeData($data)
    {
        $file = fopen($this->filename, 'w');

        $headers = array(
            'Ticket ID',
            'Description',
            'Status',
            'Priority',
            'Agent ID',
            'Agent Name',
            'Agent Email',
            'Contact ID',
            'Group ID',
        );
        fputcsv($file, $headers);

        foreach ($data['tickets'] as $ticket) {
            $row = array(
                $ticket['id'],
                $ticket['description'],
                $ticket['status'],
                $ticket['priority'],
                $ticket['assignee_id'],
                $ticket['assignee_id'],
                $ticket['requester_id'],
                $ticket['group_id'],
            );
            fputcsv($file, $row);
        }

        fclose($file);
    }
}

$url = 'https://relokia67.zendesk.com/api/v2/tickets.json';
$username = 'andrygtfo@gmail.com';
$password = 'ah4$p*6p35xW..3';

$zendeskAPI = new ZendeskAPI($url, $username, $password);
$data = $zendeskAPI->fetchData();

$csvWriter = new CSVWriter('tickets.csv');
$csvWriter->writeData($data);

echo 'Дані успішно збережено у файл tickets.csv';

