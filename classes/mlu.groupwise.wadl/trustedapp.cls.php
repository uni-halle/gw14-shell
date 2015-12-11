<?php

namespace mlu\groupwise\wadl;
use mlu\rest\wadlProxy, mlu\groupwise\apiResult, mlu\groupwise\xsd;
/**
 * rest-api-proxies runtime-file
 * 
 * @author wadl-abstract.php
 * @package gwProxy
 * @subpackage api-gen
 * @category Rest-Api
 */


/**
 * dynamic abstraction for gw-class trustedapp
 * 
 * <p>this class is auto-generated and may change with wadl-change.</p>
 * 
 * @author wadl-abstract.php
 * @package gwProxy
 * @subpackage api-gen
 * @category Rest-Api
 *
 * @method static apiResult|mixed create ( xsd\trustedapp $data=null ,string $queryString=null ) <p>request: POST system/trustedapps</p>
 * @method static apiResult|mixed delete ( mixed $data=null ,string $queryString=null ) <p>request: DELETE system/trustedapps/{trustedapp}</p><p>template-var: trustedapp => xs:string : template</p>
 * @method static apiResult|mixed getList ( mixed $data=null ,string $queryString=null ) <p>request: GET system/trustedapps</p>
 * @method static apiResult|mixed object ( mixed $data=null ,string $queryString=null ) <p>request: GET system/trustedapps/{trustedapp}</p><p>template-var: trustedapp => xs:string : template</p>
 * @method static apiResult|mixed update ( xsd\trustedapp $data=null ,string $queryString=null ) <p>request: PUT system/trustedapps/{trustedapp}</p><p>template-var: trustedapp => xs:string : template</p><p>query-String: attrs => xs:string : query</p>
 */
class trustedapp extends wadlProxy { 
	/**
	 * @internal
	 */
	public function __construct() {
		$this->methods=json_decode('{"__className":"trustedapp","__isObject":true,"create":{"paramStatic":{},"paramQuery":{},"path":"system\/trustedapps","action":"POST","doc":"Create a Trusted Application object given the data. Upon success the newly created key value is returned","requestType":"trustedapp","responseType":null,"__isStatic":"object"},"delete":{"paramStatic":{"trustedapp":"xs:string : template"},"paramQuery":{},"path":"system\/trustedapps\/{trustedapp}","action":"DELETE","doc":"Remove the specified Trusted Application given the name.","requestType":null,"responseType":null},"getList":{"paramStatic":{},"paramQuery":{},"path":"system\/trustedapps","action":"GET","doc":"Get a list of Trusted Applications in this System.","requestType":null,"responseType":null,"__isStatic":"object"},"object":{"paramStatic":{"trustedapp":"xs:string : template"},"paramQuery":{},"path":"system\/trustedapps\/{trustedapp}","action":"GET","doc":"Get a specific Trusted Application object given the name","requestType":null,"responseType":null},"update":{"paramStatic":{"trustedapp":"xs:string : template"},"paramQuery":{"attrs":"xs:string : query"},"path":"system\/trustedapps\/{trustedapp}","action":"PUT","doc":"Update a Trusted Application object given the data.","requestType":"trustedapp","responseType":null}}');
	}
	/**
	 * Create a Trusted Application object given the data. Upon success the newly created key value is returned
	 * @internal
	 *
	 * @param apiResult|xsd\trustedapp $data user data; may repeat which will result in a merge
	 * @param string $queryString a whole query-string or one part like var=value; may repeat
	 * @return apiResult|mixed
	 *
	 * <p>request: POST system/trustedapps</p>
	 */
	protected function _create ($data=null,$queryString=null) { 
		return $this->doRequest ('create',$data,$queryString);
	}
	/**
	 * Remove the specified Trusted Application given the name.
	 * @internal
	 *
	 * @param apiResult|mixed $data user data; may repeat which will result in a merge
	 * @param string $queryString a whole query-string or one part like var=value; may repeat
	 * @return apiResult|mixed
	 *
	 * <p>request: DELETE system/trustedapps/{trustedapp}</p>
	 * <p>template-var: trustedapp => xs:string : template</p>
	 */
	protected function _delete ($data=null,$queryString=null) { 
		return $this->doRequest ('delete',$data,$queryString);
	}
	/**
	 * Get a list of Trusted Applications in this System.
	 * @internal
	 *
	 * @param apiResult|mixed $data user data; may repeat which will result in a merge
	 * @param string $queryString a whole query-string or one part like var=value; may repeat
	 * @return apiResult|mixed
	 *
	 * <p>request: GET system/trustedapps</p>
	 */
	protected function _getList ($data=null,$queryString=null) { 
		return $this->doRequest ('getList',$data,$queryString);
	}
	/**
	 * Get a specific Trusted Application object given the name
	 * @internal
	 *
	 * @param apiResult|mixed $data user data; may repeat which will result in a merge
	 * @param string $queryString a whole query-string or one part like var=value; may repeat
	 * @return apiResult|mixed
	 *
	 * <p>request: GET system/trustedapps/{trustedapp}</p>
	 * <p>template-var: trustedapp => xs:string : template</p>
	 */
	protected function _object ($data=null,$queryString=null) { 
		return $this->doRequest ('object',$data,$queryString);
	}
	/**
	 * Update a Trusted Application object given the data.
	 * @internal
	 *
	 * @param apiResult|xsd\trustedapp $data user data; may repeat which will result in a merge
	 * @param string $queryString a whole query-string or one part like var=value; may repeat
	 * @return apiResult|mixed
	 *
	 * <p>request: PUT system/trustedapps/{trustedapp}</p>
	 * <p>template-var: trustedapp => xs:string : template</p>
	 * <p>query-String: attrs => xs:string : query</p>
	 */
	protected function _update ($data=null,$queryString=null) { 
		return $this->doRequest ('update',$data,$queryString);
	}
}