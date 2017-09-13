<?php
/**
 * Created by PhpStorm.
 * User: VietAnh
 * Date: 28/08/2015
 * Time: 11:25 AM
 */

namespace App\Helper;

class Helpers {

	public function formatNumber( $number ) {
		$number = preg_replace( '/[^0-9\.-]/', "", $number );
		$number = floatval( $number );
		$number = rtrim( rtrim( number_format( $number, 2, ".", "," ), '0' ), '.' );

		return $number;
	}



	public function cutStringByChar($string, $max_length)
	{
		$string = strip_tags($string);
		if (mb_strlen($string, "UTF-8") > $max_length) {
			$max_length = $max_length - 3;
			$string = mb_substr($string, 0, $max_length, "UTF-8");
			$pos = strrpos($string, " ");
			if ($pos === false) {
				return substr($string, 0, $max_length) . "...";
			}
			return substr($string, 0, $pos) . "...";
		} else {
			return $string;
		}
	}



	public function removeVietnameseChar($string){

		$default = array(
			'/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ|å/' => 'a',
			'/À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ/' => 'A',
			'/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ|ë/' => 'e',
			'/È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ/' => 'E',
			'/ì|í|ị|ỉ|ĩ|î/' => 'i',
			'/Ì|Í|Ị|Ỉ|Ĩ/' => 'I',
			'/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ|ø/' => 'o',
			'/Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ/' => 'O',
			'/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ|ů|û/' => 'u',
			'/Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ/' => 'U',
			'/ỳ|ý|ỵ|ỷ|ỹ/'	=> 'y',
			'/Ỳ|Ý|Ỵ|Ỷ|Ỹ/'	=> 'Y',
			'/đ/' => 'd',
			'/Đ/' => 'D',
			'/ç/' => 'c',
			'/ñ/' => 'n',
			'/ä|æ/' => 'ae',
			'/ö/' => 'oe',
			'/ü/' => 'ue',
			'/Ä/' => 'Ae',
			'/Ü/' => 'Ue',
			'/Ö/' => 'Oe',
			'/ß/' => 'ss'
		);
		return preg_replace(array_keys($default), array_values($default), $string);
	}


	public function build_post_name($text){
		$new_text = $this->removeVietnameseChar($text);
		$new_text = str_replace(' ','-',$new_text);
		$new_text = str_replace('.','',$new_text);
		$new_text = str_replace('?','',$new_text);
		$new_text = str_replace(',','',$new_text);
		$new_text = str_replace(';','',$new_text);
		$new_text = str_replace(':','',$new_text);
		$new_text = str_replace('"','',$new_text);
		$new_text = str_replace("'",'',$new_text);

		return $new_text;
	}

	public function uploadImage(){

	}

}