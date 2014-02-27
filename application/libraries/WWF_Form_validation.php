<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Form Validation Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Validation
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/form_validation.html
 */
class WWF_Form_validation extends CI_Form_validation{

	/**
	 * Constructor
	 */
	public function __construct($rules = array())
	{
		parent::__construct($rules);
	}
	
	// --------------------------------------------------------------------
 
	/**
	 * Data update시 자기 자신의 값을 제외하고 유일한 값인지 체크
	 *
	 * @return	bool
	 */
	public function is_update_unique($str, $field) {
		//echo 'is_update_unique() 호출';
		list($table, $field,$value)=explode('.', $field);
		
		$this->CI->db->where($field.' !=', $value);
		$query = $this->CI->db->limit(1)->get_where($table, array($field => $str));
		
		return $query->num_rows() === 0;
    }

	// --------------------------------------------------------------------

	/**
	 * Data update시 자기 자신의 값을 제외하고 유일한 값인지 체크
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	public function valid_bizno($str, $field) {
		list($str1, $str2)=explode('.', $field);
		
		$str = $str1.$str2.$str; 

		if (strlen($str) != 10 || !$this->numeric($str)) return false;
		
		$str = str_split($str);
		$checkVal = str_split('137137135');
		$intKey = 0 ;
	
		for ($i = 0; $i < count($checkVal); $i++) {
			$intKey += intval($str[$i]) * intval($checkVal[$i]);
		}
	
		$intKey += (intval($str[8]) * 5) / 10;
		$intKey = $intKey % 10;
		$intKey = ($intKey == 0) ? 0 : 10 - $intKey;
	
		return ($intKey != intval($str[9])) ? false : true;
    }

	// --------------------------------------------------------------------

	/**
	 * hidden_required
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	public function required_hidden($str) {
		//echo 'required_hidden:'.$str;
		if ( ! is_array($str))
		{
			return (trim($str) == '') ? FALSE : TRUE;
		}
		else
		{
			return ( ! empty($str));
		}
	}

	// --------------------------------------------------------------------

	/**
	 * numeric_dash with dashes
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	public function numeric_dash($str)
	{
		return ( ! preg_match("/^([0-9-])+$/i", $str)) ? FALSE : TRUE;
	}


}
// END WWF Form Validation Class

/* End of file WWF_Form_validation.php */
/* Location: ./application/libraries/WWF_Form_validation.php */
