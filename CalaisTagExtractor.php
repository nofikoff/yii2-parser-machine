<?php
// 12/2016 Novikov.ua
// source https://groups.drupal.org/node/38290/
// provider http://www.opencalais.com/opencalais-api/
// manual  http://www.opencalais.com/wp-content/uploads/folder/ThomsonReutersOpenCalaisAPIUserGuide061016.pdf
// выделяет на основе машинного обучения по API теги из английского текста (вроде поддержвает итальянский итретий но надо разбьираться)
// теги из жерева Википедии
// также умеет выделять отласль текста
// названия капмания и ФИО персоналия

namespace nofikoff\parsermachine;


class CalaisTagExtractor
{
    /**
     * Errors array init....
     * @var array
     */
    public $_errors = array();

    /**
     * Request URL init...
     * @var string
     */
    protected $_url = 'https://api.thomsonreuters.com/permid/calais';

    /**
     * request function info....
     *
     * @param string $accessToken .....
     * @return array / json Response array.....
     */
    public function request($accessToken, $post_content)
    {
        $this->_errors = array();

        if (empty($accessToken)) {
            $this->_errors = array('Please enter unique access key as 1st parameter');
            return false;
        }

        // Init Header Params
        $headers = array(
            'X-AG-Access-Token: ' . $accessToken,
            "Content-Type: text/raw",
            'Content-length:' . strlen($post_content),
            'outputformat:application/json'
        );

        // Init Curl
        $curlOptions = array(
            CURLOPT_URL => $this->_url,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POSTFIELDS => $post_content,
        );

        $ch = curl_init();
        curl_setopt_array($ch, $curlOptions);

        // send request and get response from api..........
        $response = curl_exec($ch);

        // check cURL errors............
        if (curl_errno($ch)) {
            $this->_errors = curl_error($ch);
            //print_r($this -> _errors);
            curl_close($ch);
            return false;
        } else {
            curl_close($ch);
            $d = (json_decode($response, true));
            $result = [];
            foreach ($d as $key => $t) {
                if (isset($t['_typeGroup']) AND $t['_typeGroup'] == 'socialTag') {
                    $result[] = $t['name'];
                }
            }

            if (!sizeof($result)) return ['notag'];
            return $result;
        }
    }
}


