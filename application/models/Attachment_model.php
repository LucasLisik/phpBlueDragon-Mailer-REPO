<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author:    Lukasz Sosna
 * @e-mail:    lukasz.bluedragon@gmail.com
 * @www:       http://phpbluedragon.pl
 * @copyright: 4-9-2015 12:31
 *
 */
 
class Attachment_model extends CI_Model 
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function SelectAttachment($IdMessage)
    {
        $QueryResult = $this->db->query('SELECT * FROM 
		{PREFIXDB}attachment 
		WHERE
		attachment_message_id = "'.$this->db->escape_str($IdMessage).'"
		ORDER BY attachment_id ASC
		');

        return $QueryResult;
    }
    
    public function AddAttachement($MessageId,$FileName)
    {
        $QueryResult = $this->db->query('INSERT INTO 
		{PREFIXDB}attachment
		(
		attachment_user_id,
        attachment_message_id,
        attachment_file
		)
		VALUES
		(
        "'.$this->db->escape_str($_SESSION['user_id']).'",
		"'.$this->db->escape_str($MessageId).'",
		"'.$this->db->escape_str($FileName).'"	
		)
		');
    }
    
    public function DeleteAttachement($AttachmentId)
    {
        $QueryResult = $this->db->query('DELETE FROM 
		{PREFIXDB}attachment
		WHERE
		attachment_id = "'.$this->db->escape_str($AttachmentId).'"
		');
    }
}

?>