<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Request;

class AuthController extends Controller
{
    private $params;
    public function index(){
        $teachers = DB::table('teacher')->get();
        $this->params = array(
            "title" => "二十佳教师投票",
            "teachers" => $teachers
        );
        return view('vote', $this->params);
    }

    public function auth(){
        $input = Request::input();
        switch ($input['action']){
            case "1":
                session(['error'=>"0"]);
                $url = "http://210.42.121.241/servlet/Login";
                $this->requestData = array(
                    "id" => $input['id'],
                    "pwd" => md5($input['pwd']),
                    "xdvfb" => $input['xdvfb']
                );
                $cookie = "sto-id-20480=". $input['stoid'] . "; JSESSIONID=" . $input['jsession'];
                $paramStrig = http_build_query($this->requestData);
                $result = $this->juhecurl($url, $paramStrig, 1, $cookie);
                $url = "http://210.42.121.241/stu/student_information.jsp";
                $result = $this->juhecurl($url, false, 0, $cookie);
                $result = mb_convert_encoding($result, 'UTF-8', 'UTF-8, GBK, GB2312, BIG5');
                $isSuccess = preg_match('/<h2>(.*?)<\/table>/ims', $result, $match);

                if ($isSuccess){
                    preg_match_all('/<td(.*?)>(.*?)<\/td>/ims', $match[0], $per);
                    session(['code'=>$per[2][0]]);
                    session(['name'=>$per[2][1]]);
                    session(['sex'=>$per[2][2]]);
                    session(['idCard'=>$per[2][3]]);
                    session(['birth'=>$per[2][4]]);
                    session(['home'=>$per[2][5]]);
                    session(['college'=>$per[2][6]]);
                    session(['major'=>$per[2][7]]);
                    session(['error'=>"2"]);

                }
                else{
                    session(['code'=>null]);
                    session(['error'=>"1"]);
                }
                break;
            case "2":
                session(['error'=>"0"]);
                $input = Request::input();
                $date = date('d');

                if (DB::select('SELECT * FROM student WHERE code = ?', [session('code')])){
                    DB::delete('DELETE FROM student WHERE code = ?', [session('code')]);
                    DB::insert('INSERT into student (name, major, one, two, three, four, five, six, seven, eight, nine, ten, eleven, twelve, code, date) VALUE (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())', [session('name'), session('major'), $input['choice'][0], $input['choice'][1], $input['choice'][2],  $input['choice'][3], $input['choice'][4], $input['choice'][5], $input['choice'][6], $input['choice'][7], $input['choice'][8], $input['choice'][9], $input['choice'][10], $input['choice'][11], session('code')]);
                    return 1;
                }
                else{
                    DB::insert('INSERT into student (name, major, one, two, three, four, five, six, seven, eight, nine, ten, eleven, twelve, code, date) VALUE (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())', [session('name'), session('major'), $input['choice'][0], $input['choice'][1], $input['choice'][2],  $input['choice'][3], $input['choice'][4], $input['choice'][5], $input['choice'][6], $input['choice'][7], $input['choice'][8], $input['choice'][9], $input['choice'][10], $input['choice'][11], session('code')]);
                    return 2;
                }
                break;
            default:
                break;


        }
        $teachers = DB::table('teacher')->get();
        $this->params = array(
            "title" => "二十佳教师投票",
            "teachers" => $teachers
        );
        return view('vote', $this->params);
    }



    private function juhecurl($url,$params=false,$ispost=0,$cookie){
        $httpInfo = array();
        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
        curl_setopt( $ch, CURLOPT_USERAGENT , 'JuheData' );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 0 );
        curl_setopt( $ch, CURLOPT_TIMEOUT , 0);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt( $ch, CURLOPT_COOKIE, $cookie);

        if( $ispost )
        {
            curl_setopt( $ch , CURLOPT_POST , true );
            curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
            curl_setopt( $ch , CURLOPT_URL , $url );
        }
        else
        {
            if($params){
                curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
            }else{
                curl_setopt( $ch , CURLOPT_URL , $url);
            }
        }
        $response = curl_exec( $ch );
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
        $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
        curl_close( $ch );

        return $response;
    }
}