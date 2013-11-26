<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2012 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * LA Katalogs controller
 *
 * $URL: e107_plugins/la_katalogs/controllers/index.php $
 * $Id: index.php Rolla $
 *
 */

/**
 *
 * @package     e107
 * @subpackage	frontend
 * @version     $Id$
 *	Liepājas Apagāds katalogs
 */
 
// Testing

	//$ec = new eResponse();  
	//print_r($ec);
	//$cm = get_class_methods($ec);
	//echo "<pre>";
	//print_r($cm);
	//echo "</pre>";
	//$ec->setTitle("Shit!");

//

	session_start(); // start up your PHP session!

	if(XPN_SLD_DEBUG == true){
		ini_set('display_errors', 'On');
		error_reporting(E_ALL | E_STRICT);
	}
	
	define("XPN_SLD_INIT", true);

class plugin_xpand_slider_index_controller{

	/**
	 * Plugin name - used to check if plugin is installed
	 * Set this only if plugin requires installation
	 * @var string
	 */
	 
	
	 
	protected $debug = XPN_SLD_DEBUG; // priekš SQL debug
	 
	protected $plugin = 'XpandSlider';
	
	protected $_start = 0;
	
	protected $_lim = 100;
	
	protected $_order = 'DESC'; // kā kārtot
	
	protected $_order_by = 'id'; // pēc kā kārtot
	
	protected $_preces = array();
	
	protected $_kategorijas = array();
	
	protected $_razotaji = array();
	
	protected $_pre_skaits = null; // cik kopa rezultāti precēm
	
	protected $_raz_skaits = null; // cik kopa rezultāti ražotājiem
	
	protected $_kat_skaits = null; // cik kopa rezultāti kategorijām
	
	protected $_vkat_skaits = null; // cik kopa rezultāti vienā kategorijām
	
	protected $_pre = '<div class="ajax-data"><div class="kat-nosaukums">Preces</div><div class="gridster"><ul>';
	
	protected $_post = '</ul></div></div><div class="notirit"></div><div class="navigation"><a>ielādēt</a></div>';
	
	protected $_Kat = 'visas';
	
	protected $_vKat = null; // lai zinātu priekš kuras kategorijas meklēt rezultātus
	
	protected $_sKat = null; // lai zinātu priekš kuras sub kategorijas meklēt rezultātus
	
	protected $_nosaukums = null;
	
		//echo $this->_start;
		//e107::lan('faqs', 'front');
		//e107::css('faqs','faqs.css');
		//$this->setResponse("eResponse");
		//$this->addTitle("test");
		
	#metode kas atgriež kļudas paskaidrojumu
	public function errorCode($code=null){
		switch($code){
			case 101:
				return 'no data from db';
			break;
			case 102:
				return '';
			break;
			default:
			return false;
		}
	}
	

	/*
	#	uzstāda Query starta vērtību un limita vērtību
	#	$start
	#	$lim
	#
	*/

	public function uzstadaSL($start=0,$lim=100){
	
		if(isset($start) && is_numeric($start) && $start>0){
			$this->_start = $start;
		}else{
			$this->_start = 0;
		}
		
		/*
		else{
			if(isset($_GET['start']) && $_GET['start']>=0){
				$start = $_GET['start'];
				$this->_start = $start;
			}
		}
		*/
		
		if(isset($lim) && is_numeric($lim) && $lim<100 && $lim>0){
			$this->_lim = $lim;
		}else{
			$this->_lim = 100;
		}
			/*
			if(isset($_GET['lim']) && $_GET['lim']<0 && $_GET['lim']>100){
					$this->_lim = 100;
				}else{
					$lim = $_GET['lim'];
					$this->_lim = 100;
				}
			*/
		//}

	}
	
	public function smuksJson($json){
		header('Content-Type: application/json; charset=utf-8');
			if(version_compare(PHP_VERSION, '5.4', '>=')){
				// Add option "JSON_PRETTY_PRINT" in case you care more readability than to save some bits
				$data = json_encode($json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
			}else{
				$data = preg_replace('/\\\\u([a-f0-9]{4})/e', "iconv('UCS-4LE', 'UTF-8', pack('V',  hexdec('U$1')))", json_encode($json));
				$data = str_replace('\\/', '/', $data);
			}
			echo $issetcallback ? $callback . '(' . $data . ')' : $data;
	}
	
	
	public function utf8_urldecode($str){
		$str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($str));
		$str = html_entity_decode($str,null,'UTF-8');
		//$str="šitā-ir-mana-stringa";
		$str = preg_replace("#[-]#"," ",$str); // novāc šito-zīmi-starp-rezultātiem
		//echo $str;
		return $str;
	}
	

	/*
	#	iegūst adreses pēc katra slash
	#	0 - beidzamo, 1 - pirmspēdējo/beidzamo, 2 - pirmspedejo, 3 visu masīvu
	#
	*/

	public function iegutURL($i = 0){
		//$requestUri = $_SERVER['REQUEST_URI'];
		$requestUri = $_GET['url'];
		# Remove query string
		//$requestUri = trim(strstr($requestUri, '?', true), '/');
		$requestUri = preg_replace('#\?.*#', '', $requestUri);
		# Note that delimeter is '/'
		$arr = explode('/', $requestUri);
		$count = count($arr);
		switch($i){
			case 1:
			return $arr[$count - 2]."/".$arr[$count - 1];
			break;
			case 2:
			return $arr[$count - 2];
			break;
			case 3:
			//$this->pDebug($arr);
			return $arr;
			break;
			default:
			return $arr[$count - 1];
		}
	}
	
	# metode, kas rēkina pozīcijas priekš režga
	# $w - režģa konteinera kopējais platums, $pw - cik viens režģis plats, $ph - cik viens režģis augsts, $inp = cik režģi lapā, $page - kura lapa, $cnt = cik preces šijā lapā
	
	public function izrekinatPos($w=1000, $pw=186, $ph=186, $inp=10, $page=1, $cnt=10){
	
		//SIMULATION
		//$inp=10;
		//$page = 1;
		//$cnt = 6;
		//$hrn = 2; // how rows needed for one page
		
		$hgh = round($w/$pw); // how grids horizontaly izrēķina cik režģus blakus var salikt
		$hrn = round($inp/$hgh); // how rows needed for one page
		
		//$this->pDebug('inp: '.$inp);
		//$this->pDebug('page: '.$page);
		//$this->pDebug('cnt: '.$cnt);
		//$this->pDebug('hgh: '.$hgh);
		//$this->pDebug('hrn: '.$hrn);
		
		if($hgh>$cnt){ // ja preču sk. mazāks par cik blakus var salikt
			$bl = 1; // big loop
			$sl = $cnt; //sm loop
		}else{
			$cntt = $cnt/$hgh;
			$cntt = ceil($cntt);
			//$this->pDebug('cntt: '.$cntt);
			$bl = $cntt; // big loop
			//$sl = $cnt; //sm loop
		}

		$res = array();
		//$res[pos] = array();
		
		if($page===1){
			
			for($r=1;$r<=$bl;$r++){ // lielā rindas cilpa
				
				//$this->pDebug('Lielā cilpa: '.$r);
				
				if($r>1){
					$row = $r;
				}else{
					$row = 1;
				}
				
				if($r == $bl){
					//$this->pDebug('DEBUG');
					
					if($hgh>$cnt){
					
						$cpp = $sl;
						
						for($i=1;$i<=$cpp;$i++){
							$res[pos][] = array('row' => $row, 'col' => $i);
						}
					
					}else{
					
						$cpp = ceil($cnt/$hgh); //
						//$this->pDebug('cpp: '.$cpp);
						
						$cpp = $cpp*$hgh;
						//$this->pDebug('cpp: '.$cpp);

						$cpp = $cpp-$cnt;
						//$this->pDebug('cpp: '.$cpp);
						
						$cpp = $hgh-$cpp;
						//$this->pDebug('cpp: '.$cpp);
						
						if($cpp!=0){
							$cpp = $cpp;
						}else{
							$cpp = $hgh;
						}
						
						for($i=1;$i<=$cpp;$i++){
							$res[pos][] = array('row' => $row, 'col' => $i);
						}
		
					}
				
				}else{
				
					for($i=1;$i<=$hgh;$i++){
						$res[pos][] = array('row' => $row, 'col' => $i);
					}
					
				
				}
				
				
			}
		
		}else{
		
			$row = $page+$page-1;
			
			for($r=1;$r<=$bl;$r++){ // lielā rindas cilpa
				
				//$this->pDebug('Lielā cilpa: '.$r);
			
				if($r==1){
					$row = $row;
				}else{
					$row++;
				}
					
				if($r == $bl){
					//$this->pDebug('DEBUG');
					
					if($hgh>$cnt){
					
						$cpp = $sl;
					
						for($i=1;$i<=$cpp;$i++){
							$res[pos][] = array('row' => $row, 'col' => $i);
						}
						
					}else{
					
						$cpp = ceil($cnt/$hgh); //
						//$this->pDebug('cpp: '.$cpp);
						
						$cpp = $cpp*$hgh;
						//$this->pDebug('cpp: '.$cpp);

						$cpp = $cpp-$cnt;
						//$this->pDebug('cpp: '.$cpp);
						
						$cpp = $hgh-$cpp;
						//$this->pDebug('cpp: '.$cpp);
						
						if($cpp!=0){
							$cpp = $cpp;
						}else{
							$cpp = $hgh;
						}
						
						for($i=1;$i<=$cpp;$i++){
							$res[pos][] = array('row' => $row, 'col' => $i);
						}
						
					}
				
				}else{
				
					for($i=1;$i<=$hgh;$i++){
						$res[pos][] = array('row' => $row, 'col' => $i);
					}
					
				}
				
			}
		
		}
		
		//$this->pDebug($res);
		return $res;
	
	}
	
	public function breadCrumbKat($sep='->'){
	
		//$this->izrekinatPos();
	
		//echo 'palaist!!!!';
		
		$url = $this->iegutURL(3);
		
		//unset($url[0], $url[1]); // izvāc nevajadzīgos masīva ierakstus
		
		$kat = $this->iegutKat();
		//$k = 1;

		$res;
		//$urls = array();
		
		//foreach($url as $ur){ // pārkārto lai sākas ar 0
			//$urls[] = $ur;
		//}
		
		$sk = count($url);
		
		//$res .= $_GET['url'];
		
		//foreach($url as $urs){ // pārkārto lai sākas ar 0
			//$res .= $urs.' ';
		//}
		
		//$this->pDebug($urls);
		$res .= '<a href="'.LA_KAT_BASE.LA_KAT_SEF.'">'.LAN_PLUGIN_LA_KAT_BREADCRUMB_HOME.'</a>';	
		for($i=0; $i<$sk; $i++){
			//echo 'DEBUG';
			//$urls[$i] = $url[$i].'/';
			if($i===0){
				// process first element
				$href .= 'kategorija/';
			}else if($this->parbauditKat('visas')){
				$href .= 'visas';
				$res .= '<a href="'.LA_KAT_BASE.LA_KAT_SEF.$href.'" class="js-as">'.LAN_PLUGIN_LA_KAT_BREADCRUMB_VISAS.'</a>';
			}else{
				$nos = $this->mekleM($kat, 'alias', $url[$i]);
				if($nos){
					//$this->pDebug($nos);
					//$href .= $urls[$i-$k];
					$href .= '/'.$url[$i];
					$res .= '<a href="'.LA_KAT_BASE.LA_KAT_SEF.$href.'" class="js-as">'.$nos[0][nosaukums].'</a>';
				}
				
			}
			
			if($i != $sk-1){
				// process last element
				$res .= ' '.$sep.' ';
			}
			
			//$res .= ' i:<strong>'.$i.'</strong> ';
			
		}
		
		//$res .= ' sk:<strong>'.$sk.'</strong> ';
		
		return $res;
		
	}
	
	
	public function flatten_array($array, $preserve_keys = 2, &$out = array(), &$last_subarray_found, &$i = 0){
		//$i = 0;
		//$k = 0;
		foreach($array as $key => $child){
		
		//$this->pDebug($i);
		
			if(is_array($child)){
				$last_subarray_found = $key;
				$out = $this->flatten_array($child, $preserve_keys, $out, $last_subarray_found, ++$i);
				//$out[] = 'ir';
				//$i++;
			}else if($preserve_keys + is_string($key) > 1){
				if($last_subarray_found){
					//$sfinal_key_value = $last_subarray_found . "_" . $key;
					$sfinal_key_value = $key;
					$out[$i][$sfinal_key_value] = $child;
					
					
					
				}else{
				
					$sfinal_key_value = $key;
					$out[$i][$sfinal_key_value] = $child;
					//$k++;
					//$i++;
				}
				
				//$i++;
			}else{
				$out[$i][] = $child;
				//$i++;
			}
			//$k++;
		}
		//$i++;
		return $out;
	}
	
	public function increment(&$var){
		$var++;
	}
	
	/*
	public function search($array, $key, $val){
		$results = array();
		foreach($array as $keys => $value){
			if(is_array($value)){
				if(isset($value[$key]) && $value[$key] == $val)
					$results[] = $keys;
					//$results[] = $array;
					
				foreach($value as $subarray){
					$results = array_merge($results, $this->search($subarray, $key, $value));
				}
			}
		}
		
		return $results;
	}
	*/
	
	public function find_parent($array, $kneedle, $needle, $parent = null,  $i = 0){
	
		foreach($array as $key => $value){
			if(is_array($value)){
				$pass = $parent;
				if(is_string($key)){
					$pass = $key;
				}
				$found = $this->find_parent($value, $kneedle, $needle, $pass, $i);
				
				if($found !== false){
					return $found;
				}
			}else if($key == $kneedle && $value == $needle){
				return $i-1;
			}
			$i++;
		}
		return false;
	}
	
	# breadCpre - bread crumb prepare
	
	public function breadCpre($kat = array(), $katid=null){
	
		unset($refs);
		unset($list);
		$refs = array();
		$list = array();
		$r = 0;
		
		foreach($kat as $row){
			$ref 			= &$refs[$row['id']];
			$ref['id'] 		= $row['id'];
			$ref['parent'] 	= $row['parent'];
			$ref['name']	= $row['nosaukums'];
			$ref['alias']	= $row['alias'];
			
			if($row['parent'] == 0){
				$list[$row['id']] = &$ref;
				$r = 0;
			}else{
				$refs[$row['parent']]['children'][$row['id']] = &$ref;
				$r++;
			}
			
			if($ref['id']==$katid){ // ja atrada id stop cilpu
				break;
			}
		}
		
		
		//$parentsort = array();
			//foreach ($list as $key => $row){
			//	$parentsort[$key] = $row['parent'];
			//}
			
		//array_multisort($parentsort, SORT_ASC, $list);
		
		$rev = array_reverse($list);
		//$search = $this->find_parent($rev, 'id', $katid);
		//$this->pDebug($rev[0]);
		//$rev = $rev[$search];
		
		$result = $this->flatten_array($rev[0]);
		//$this->pDebug($result);
		
		$resu = array();
		foreach($result as $key){
			$resu[] = $key;
		}
		
		return $resu;
	}
	
	# TODO - vajag nopietnāku breadcrumb risinājumu!!!
	
	public function breadCrumb($sep='->'){
		
		$url = $this->iegutURL(3);
		
		if($prece = $this->parbauditKat('prece')){ // ja ir iekš url prece
			$kat = $this->iegutKat(false, 'ORDER BY la_kat_kat_parent ASC');
			$pre = $this->iegutPre();
			$katid = $pre[0][kategorija];
			$result = $this->breadCpre($kat, $katid);
			//$this->pDebug($result);
			
			/*
			$i = 0;
			foreach($result as $key){
				//$this->pDebug($key[alias]);
				$ali = $this->mekU('siksnas');
				
				//$this->pDebug($ali);
				
				if($ali[key]){
					echo ' izpildās i:'.$i;
					unset($result[$i]);
				}
				$i++;
			}
			*/
			
			//$this->pDebug($result);
			
			$i = 0;
			$res;
			$sk = count($result);
			
			// process first element
			$res .= '<a href="'.LA_KAT_BASE.LA_KAT_SEF.'">'.LAN_PLUGIN_LA_KAT_BREADCRUMB_HOME.'</a>';
			$res .= ' '.$sep.' ';
			
			
			foreach($result as $key => $val){
				//$res .= 'sk='.$sk.' ';
				//$res .= 'i='.$i.' ';
				if($i==0){
					$href .= 'kategorija/';
					$href .= $val[alias].'/';
					$res .= '<a href="'.LA_KAT_BASE.LA_KAT_SEF.$href.'" class="js-as">'.$val[name].'</a>';
					$res .= ' '.$sep.' ';
				}else if($i==$sk-1){ // pirmsbeidzamā cilpa
					$href .= $val[alias].'/';
					$res .= '<a href="'.LA_KAT_BASE.LA_KAT_SEF.$href.'" class="js-as">'.$val[name].'</a>';
				}else{
					$href .= $val[alias].'/';
					$res .= '<a href="'.LA_KAT_BASE.LA_KAT_SEF.$href.'" class="js-as">'.$val[name].'</a>';
					$res .= ' '.$sep.' ';
				}
				$i++;
			}
						
			//$parent = $pre[0][parent];
		}else if($kate = $this->parbauditKat('kategorija')){
			//$this->pDebug($kate);
			//$katid = $kate[id];
			//$parent = $kate[parent];
			$res = $this->breadCrumbKat($sep);
			
			//$this->pDebug($result);
			/*
			$parentsort = array();
			foreach ($result as $key => $row){
				$parentsort[$key] = $row['parent'];
			}
			
			array_multisort($parentsort, SORT_ASC, $result);
			
			$i = 0;
			foreach($result as $key){
				if(!$this->mekU($key[alias])){
					//echo 'izpildās i: '.$i;
					unset($result[$i]);
				}
				$i++;
			}
			*/
			//echo $katid;
			
		}
		
		return $res;
	}
	
	public function katNav($type=null){
		$sql = e107::getDB(); 	// mysql class object
		$sql->db_Set_Charset("utf8");
		if($kategorijas = $sql->retrieve(DB_KATEGORIJAS, '*', 'la_kat_kat_parent=0 AND la_kat_kat_klase IN ('.USERCLASS_LIST.') ORDER BY la_kat_kat_order ASC', true)){
			$url = $this->iegutURL(3);
			$kat = array_search('kategorija', $url);
			$visas = array_search('visas', $url);
			$i = $kat-1;
			//echo $url[$kat+1];
			
			//echo LA_KAT_BASE;
			
			//if($url == 'kategorija/visas' || $url == LA_KAT_DIR){
			$res = '<div class="kategoriju-izvelne"><ul class="kategorijas nav nav-pills">';
				if($visas){
					$res .= '<li class="hilite"><a href="'.LA_KAT_BASE.LA_KAT_SEF.'kategorija/visas" data-alias="visas" class="aktiva active js-as" data-laid="visas">'.LAN_PLUGIN_LA_KAT_KAT_I_VISAS.'</a></li>
					';
				}else{
					$res .='<li class="parasta"><a href="'.LA_KAT_BASE.LA_KAT_SEF.'kategorija/visas" data-alias="visas" class="parasta js-as" data-laid="visas">'.LAN_PLUGIN_LA_KAT_KAT_I_VISAS.'</a></li>';
				}
	
				foreach($kategorijas as $key=>$value){
					$res .= '<li ';
						if($url[$kat+1] == $value["la_kat_kat_alias"]){
							$res .=	'class="hilite"><a href="'.LA_KAT_BASE.LA_KAT_SEF.'kategorija/'.$value["la_kat_kat_alias"].'" data-alias="'.$value["la_kat_kat_alias"].'" class="selected aktiva active js-as" data-laid='.$value["la_kat_kat_id"].'>'.$value["la_kat_kat_nosaukums"].'</a></li>';
						}else{
							$res .=	'class="parasta"><a href="'.LA_KAT_BASE.LA_KAT_SEF.'kategorija/'.$value["la_kat_kat_alias"].'" data-alias="'.$value["la_kat_kat_alias"].'" class="parasta js-as" data-laid='.$value["la_kat_kat_id"].'>'.$value["la_kat_kat_nosaukums"].'</a></li>';
						}
				/*
					if($aktiva = array_search($url[$kat+$i], $url)){
						$res .=	'<li class="hilite"><a href="'.LA_KAT_BASE.LA_KAT_SEF.'kategorija/'.$value["la_kat_kat_alias"].'" class="selected aktiva active js-as" data-laid='.$value["la_kat_kat_id"].'>'.$value["la_kat_kat_nosaukums"].'</a></li>';
					}else{
						$res .=	'<li class="parasta"><a href="'.LA_KAT_BASE.LA_KAT_SEF.'kategorija/'.$value["la_kat_kat_alias"].'" class="parasta js-as" data-laid='.$value["la_kat_kat_id"].'>'.$value["la_kat_kat_nosaukums"].'</a></li>';
					}
				*/
					//$i++;
				}
				$res .= '</ul></div>';
				return $res;
		}
	}
	
	
	#Here is a small multi-dimensional key renaming function. It can also be used to process arrays to have the correct keys for integrity throughout your app. It will #not throw any errors when a key does not exist.
	# usage multi_rename_key($tags, "url", "value");
	# more usage multi_rename_key($tags, array("url","name"), array("value","title"));
	
	public function multi_rename_key($array, $old_keys, $new_keys){
		if(!is_array($array)){
			($array=="") ? $array=array() : false;
			return $array;
		}
		foreach($array as &$arr){
			if (is_array($old_keys)){
				foreach($new_keys as $k => $new_key){
					(isset($old_keys[$k])) ? true : $old_keys[$k]=NULL;
					$arr[$new_key] = (isset($arr[$old_keys[$k]]) ? $arr[$old_keys[$k]] : null);
					unset($arr[$old_keys[$k]]);
				}
			}else{
				$arr[$new_keys] = (isset($arr[$old_keys]) ? $arr[$old_keys] : null);
				unset($arr[$old_keys]);
			}
		}
		return $array;
	}
	
	
	#print Debug
	public function pDebug($e){
		echo '<pre class="debug">';
		print_r($e);
		echo '</pre>';
		//echo $res;
	}
	
	# funkcija kas novāc piemēram la_kat_kat_ no masīva key name un atstāj tikai galējo piemēram la_kat_kat_id kļust par id
	# $arr - datu masīvs, $chars = cik char jāvāc nost default 11, jo la_kat_kat_ - ir 11 chari
	public function novaktPrefix($arr=null, $chars=11){
		// sagatavo datu izvadei, novācot la_kat_kat_
		$i = 0;
		foreach($arr as $key => $val){
			foreach($val as $k => $v){
				$old[$k] = $i; //= $k; // novāc la_kat_kat_
				$new[substr($k, $chars)] = $i;// = 
				$nval[$i] = $v;
				$i++;
			}
		}
		
		$old = array_keys($old);
		$new = array_keys($new);
		
		$arr = $this->multi_rename_key($arr, $old, $new);
		return $arr;
	}
	
	
	# kategorijām
	
	# funkcija kas iegūst apakškategorijas
	# type json vai text
	# $url_limenis - kurā līmenī
	public function iegutSubKat($kat_arr=array(), $type=null){
		$debug = (LA_KATALOGS_DEBUG) ? '<pre class="debug-func">'.__FUNCTION__.',</pre> ' : false;
		echo $debug;
		
		$sql = e107::getDB(); 	// mysql class object
		$sql->db_Set_Charset("utf8");
		
		$parent_id = $kat_arr[id];
		$url_limenis = $kat_arr[url_limenis];
		unset($kat_arr['url_limenis']);
		
		//$this->pDebug($kat_arr);
		
		$kat_arrs = array(0 => $kat_arr); // lai pareizi strādātu novaktPrefix();
		
		$kat_arrs = $this->novaktPrefix($kat_arrs);
				
		$this->_sKat = $parent_id;
		$kategorijas = $this->iegutKat();
		//$url_limenis = $url_limenis;
		
		$vkat = $this->apstradaVKD($kat_arrs);
		$vkat = $this->apstradaPPK($vkat);
		
		//$this->pDebug($kategorijas);
		
		$pnav = $this->pagNav('sKat');
		$nav = $pnav['pnav'];
		$pos = $pnav['pos'];
		
		$sub_kat = $sql->retrieve(DB_KATEGORIJAS, '*', 'la_kat_kat_parent = '.$parent_id.' AND la_kat_kat_klase IN ('.USERCLASS_LIST.') ORDER BY la_kat_kat_'.$this->_order_by.' '.$this->_order.' LIMIT '.$this->_start.', '.$this->_lim.'', true, null, $this->debug);
		
		if($sub_kat){
			//$sub_kat = $sub_kat;
			$sub_kat = $this->novaktPrefix($sub_kat);
		}else{
			$sub_kat = array('error' => true, 'code' => $this->errorCode(101));
			//return false;
		}
		
			$nos = LAN_PLUGIN_LA_KAT_KAT_I_KAT.' - '.$this->aNosaukumu($this->_sKat, $kategorijas, "kat"). ' <div class="pre-skaits">(<div class="pre-skaits-inn">'.$this->skaits(sKat).'</div>)</div>';
			$pre = '<div class="ajax-data"><div class="kat-nosaukums">'.$nos.'</div><div class="gridster">'.$vkat.'<ul>';
			$post = $this->_post = '</ul></div></div><div class="notirit"></div>';
			$nosaukums = $nos;
			//$this->pDebug($pre);
			define("e_PAGETITLE", $this->aNosaukumu($this->_sKat, $kategorijas, "kat"));
			$res = $this->apstradaKD($sub_kat, $pre, $post, $nav, $kat, $nosaukums, $url_limenis);
				
				//echo $type;
			if($type=="json"){
				$json = $res;
				//$json[err] = $res[error];
				//$json[text] = $res;
				//$this->pDebug($json);
				return $json;
			}else{
				$resp[err] = $res[error];
				$resp[text] = $this->apstradaPPK($res);
				//$this->pDebug($resp);
				return $resp;
			}
		//return false;
	}
	
	
	#	Apastrādā kategoriju datus
	#	metode, kas apstrada datus, un atgriez rezultatu masīvā
	#	$data - masīvs, $pre - html pirms preces, $post - html pec preces, $nav - priekš navigācijas $kat - kad id, $nosaukums - kas būs iekš <title>,
	#	$url_limenis - lai zinātu izvadīt pareizu url uz nākošo kat
	public function apstradaKD($data=null, $pre=null, $post=null, $nav=null, $kat=null, $nosaukums=null, $url_limenis=null){
		//$this->pDebug($data);
		$tp = e107::getParser(); 				// parser for converting to HTML and parsing templates etc. 
	
		$u_mas = $this->iegutURL(3);
		$url = $url_limenis;
		$sep = null;
		
		if($sep = array_search('visas', $u_mas)){
			$sep = "kategorija/";
		}else{
			$ukat = array_search('kategorija', $u_mas); // iegūst kurš pēc kārtas ir masīvā
			//$ukat++; // pieskaita viens, lai neatkārtotos kategorija
			for($i=0; $i<=$url; $i++){
				$sep .= $u_mas[$ukat+$i];
				$sep .= '/';
			}
		}
		
		$res['pre'] = $pre;
		$res['kategorija'] = $kat;
		$res['nosaukums'] = $nosaukums;
		
		if(!$data[error]){
			foreach($data as $key=>$value){
				$res['kategorijas'][$key]['kategorija']['id'] = $value['id'];
				$res['kategorijas'][$key]['kategorija']['sakums'] =  "<li data-katid=".$value['id']." class='kategorija' data-row=".rand(1,1)." data-col=".rand(1,1)." data-sizex=".rand(1,1)." data-sizey=".rand(1,1)."><a href='".LA_KAT_BASE.LA_KAT_SEF.$sep.$value['alias']."' class='kategorijas-saite js-as'>";
				
				$bildeM =  $value['bilde_m'];
				$bildeL =  $value['bilde_l'];
				
				$bildeM = explode(",", $bildeM);
				
				$res['kategorijas'][$key]['kategorija']['nosaukums'] =  "<div class='kategorijas-augsa'><div class='kategorijas-nosaukums'><span>".LAN_PLUGIN_LA_KAT_PR_I_NOSAUKUMS."</span> ".$tp->toHTML($value['nosaukums'], TRUE, 'TITLE')."<br /></div></div>";

				$res['kategorijas'][$key]['kategorija']['bilde'] .=  "<div class='kategorijas-bilde' data-href='".LA_KAT_BASE.LA_KAT_SEF.'datnes/php/connector.php?cmd=file&target='.$bildeL[0]."'><img src='".LA_KAT_BASE.LA_KAT_SEF.'images/'.$bildeM[0]."'/><br /></div>";
				
				$res['kategorijas'][$key]['kategorija']['kategorija'] =  "<div class='kategorijas-kat'><span>".LAN_PLUGIN_LA_KAT_PR_I_KATEGORIJA."</span> ".$tp->toHTML($this->aNosaukumu($value['kategorija'], $this->iegutKat(), 'kat'), TRUE, 'TITLE')."<br /></div>";
				
				$apraksts = $value['apraksts'];
				
				$apraksts = preg_replace("#\[\b(html)\]#","",$apraksts); //atrod ar regex tieši [html] un izvāc ārā
				$apraksts = preg_replace("#\[\/\b(html)\]#","",$apraksts); //atrod ar regex tieši [/html] un izvāc ārā
				
				$res['kategorijas'][$key]['kategorija']['apraksts'] =  "<div class='kategorijas-apraksts'>".$tp->toHTML($apraksts, TRUE, 'BODY')."<br /></div>";
				
				$res['kategorijas'][$key]['kategorija']['beigas'] =  "</a></li>";
			}
			
			
			
		}else{
			//echo "DEBUGGGGGG!";
			$res['pre'] = $pre.'<div class="alert-error kat-error"><h1>'.LAN_PLUGIN_LA_KAT_KAT_ERROR.'</hi>';
			$res['post'] =  "</div>";
			$res['error'] = $data;
			//http_response_code(404);
			//header('Content-Type: text/plain');
			//exit('404 Not Found');
		}
		
		$res['nav'] = $nav;
		$res['post'] = $post;
		$res['breadcrumb'] = $this->breadCrumb(LA_KAT_BREADC_SEP);
		
		return $res;
	}
	
	#	Apastrādā vienas kategorijas datus
	#	metode, kas apstrada datus, un atgriez rezultatu masīvā
	#	$data - masīvs,
	public function apstradaVKD($data=null){
	//$this->pDebug($data);
	$tp = e107::getParser(); 				// parser for converting to HTML and parsing templates etc. 
	
		if($data){

			foreach($data as $key=>$value){
				$res['kategorijas'][$key]['kategorija']['id'] = $value['id'];
				$res['kategorijas'][$key]['kategorija']['sakums'] =  "<div data-katid=".$value['id']." class='vkategorija'>";
				
				$bildeM =  $value['bilde_m'];
				$bildeL =  $value['bilde_l'];
				
				$bildeM = explode(",", $bildeM);
				$bildeL = explode(",", $bildeL);
				
				$res['kategorijas'][$key]['kategorija']['nosaukums'] =  "<div class='vkategorijas-augsa'><div class='vkategorijas-nosaukums'><span>".LAN_PLUGIN_LA_KAT_PR_I_NOSAUKUMS."</span> ".$tp->toHTML($value['nosaukums'], TRUE, 'TITLE')."<br /></div></div>";

				
				$res['kategorijas'][$key]['kategorija']['bilde'] .=  "<div class='vkategorijas-bilde' data-mbilde='".LA_KAT_BASE.LA_KAT_SEF.'images/'.$bildeM[0]."'><img src='".LA_KAT_BASE.LA_KAT_SEF.'datnes/php/connector.php?cmd=file&target='.$bildeL[0]."'/><br /></div>";
				
				//$res['kategorijas'][$key]['kategorija']['kategorija'] =  "<div class='vkategorijas-kat'><span>".LAN_PLUGIN_LA_KAT_PR_I_KATEGORIJA."</span> ".$tp->toHTML($this->aNosaukumu($value['kategorija'], $this->iegutKat(), 'kat'), TRUE, 'TITLE')."<br /></div>";
				
				$apraksts = $value['apraksts'];
				
				$apraksts = preg_replace("#\[\b(html)\]#","",$apraksts); //atrod ar regex tieši [html] un izvāc ārā
				$apraksts = preg_replace("#\[\/\b(html)\]#","",$apraksts); //atrod ar regex tieši [/html] un izvāc ārā
				
				$res['kategorijas'][$key]['kategorija']['apraksts'] =  "<div class='vkategorijas-apraksts'>".$tp->toHTML($apraksts, TRUE, 'BODY')."<br /></div>";
				
				$res['kategorijas'][$key]['kategorija']['beigas'] =  "</div>";
			}
			
			return $res;
		}else{
			//http_response_code(404);
			//header('Content-Type: text/plain');
			//exit('404 Not Found');
		}
	}
	
	
	#	Domāta lai izveido izvadi prieks php kategorijām, nevis json;
	#	apstrādāPriekšPHPK
	#
	
	public function apstradaPPK($res){
	//print_r($res);
		$text = $res[pre];
		foreach($res[kategorijas] as $k){
			foreach($k as $v){
				$text .= $v['sakums'];
				$text .= $v['nosaukums'];
				$text .= $v['bilde'];
				$text .= $v['kategorija'];
				$text .= $v['apraksts'];
				//$text .= $v['statuss'];
				$text .= $v['beigas'];
				//print_r($value['nosaukums']);
			}
		}
		$text .= $res[post];
		$text .= $res[nav];
		return $text;
	}
	
	
	# sagatavo visas parent kategorijas
	public function sagVisKat($type){
		//include_once("content/header.php");
		$plain = false;
		//$this->uzstadaSL(0,2); //
		
		$pnav = $this->pagNav('pKat');
		$nav = $pnav['pnav'];
		$pos = $pnav['pos'];
		
		$kategorijas = $this->iegutVPKat();
		
		//$this->pDebug($kategorijas);
		
		$nos = LAN_PLUGIN_LA_KAT_KAT_I_VISAS.' <div class="pre-skaits">(<div class="pre-skaits-inn">'.$this->skaits(pKat).'</div>)</div>';
		$pre = $this->_pre = '<div class="ajax-data"><div class="kat-nosaukums">'.$nos.'</div><div class="gridster"><ul>';
		$post = $this->_post = '</ul></div></div><div class="notirit"></div>';
		$kat = "visas";
		$nosaukums = LAN_PLUGIN_LA_KAT_KAT_I_VISAS;
		//$this->pDebug($nosaukums);
		define("e_PAGETITLE", $nosaukums);
		$res = $this->apstradaKD($kategorijas, $pre, $post, $nav, $kat, $nosaukums);
		//$this->pDebug($res);
		
		if($type=="json"){
			$json = $res;
			return $json;
			//print_r($res);
		}else{
			return $this->apstradaPPK($res);
		}
	}
	
	
	
	# /kategorijām
	
	
	
	# viss priekš precēm
	
	// iegūst visas preces no db
	# $force - vienalga vai ir jau kaut kas, iekšā masīvā, vai nav, tiks veikts pieprasījums no db
	public function iegutPre($force=false){
		$debug = (LA_KATALOGS_DEBUG) ? '<pre class="debug-func">'.__FUNCTION__.',</pre> ' : false;
		echo $debug;
		
		if(!empty($this->_preces) || $force != false){
			return $this->_preces;
		}else{
			$sql = e107::getDB(); 	// mysql class object
			$sql->db_Set_Charset("utf8");
			//echo $this->_skaits;
			// combined select and fetch function - returns an array.
			
			//$aliases = 'la_kat_pre_id as id, la_kat_pre_kods as kods, la_kat_pre_razotajs as razotajs, la_kat_pre_kategorija as kategorija, la_kat_pre_nosaukums as nosaukums, la_kat_pre_rup_nr as rup_nr, la_kat_pre_bilde_m as bilde_m, la_kat_pre_bilde_l as bilde_l, la_kat_pre_apraksts as apraksts';
			
			$preces = $sql->retrieve(DB_KATALOGS, '*', 'la_kat_pre_klase IN ('.USERCLASS_LIST.') ORDER BY la_kat_pre_'.$this->_order_by.' '.$this->_order.' LIMIT '.$this->_start.', '.$this->_lim.'', true, null, $this->debug);
			
			if($preces){
				//$sub_kat = $sub_kat;
				$preces = $this->novaktPrefix($preces);
			}else{
				$preces = array('error' => true, 'code' => $this->errorCode(101));
				//return false;
			}
			
			//$this->pDebug($preces);
			//print_r($precess);
			//echo $this->_pre_skaits;
			$this->_preces = $preces;
			return $this->_preces;
		}
	}
	
	// iegūst kategorijas
	public function iegutKat($force=false, $sort=''){
		$debug = (LA_KATALOGS_DEBUG) ? '<pre class="debug-func">'.__FUNCTION__.',</pre> ' : false;
		echo $debug;
	
		//$this->_razotaji = "";
		//$this->pDebug($_SESSION['razotaji']);
		if($force || empty($_SESSION['kategorijas'])){
			unset($_SESSION['kategorijas']);
			$sql = e107::getDB(); 	// mysql class object
			$sql->db_Set_Charset("utf8");
			// combined select and fetch function - returns an array. 
			//$razotaji = $sql->retrieve(DB_RAZOTAJI, '*', 'la_kat_raz_klase IN ('.USERCLASS_LIST.') ORDER BY la_kat_raz_'.$this->_order_by.' '.$this->_order.' LIMIT '.$this->_start.', '.$this->_lim.'', true, null, $this->debug);
			$kategorijas = $sql->retrieve(DB_KATEGORIJAS, '*', 'la_kat_kat_klase IN ('.USERCLASS_LIST.') '.$sort.'', true, null, $this->debug);
			$kategorijas = $this->novaktPrefix($kategorijas);
			//$this->pDebug($kategorijas);
			$_SESSION['kategorijas'] = $kategorijas; // store session data
			//$this->_razotaji = $razotaji;
			//return $this->_razotaji;
			return $_SESSION['kategorijas'];
		}else{
			//echo 222;
			return $_SESSION['kategorijas'];
		}
	}
	
	// meklē iekš precēm vai ir tāds alias
	# $q - alias nosaukums
	# $force - vienalga vai ir jau kaut kas, iekšā masīvā, vai nav, tiks veikts pieprasījums no db
	public function mekPreAlias($q,$force=false){
		if(!empty($this->_preces) || $force != false){
			return $this->_preces;
		}else{
			$sql = e107::getDB(); 	// mysql class object
			$sql->db_Set_Charset("utf8");
			//echo $this->_skaits;
			// combined select and fetch function - returns an array.
			
			//$aliases = 'la_kat_pre_id as id, la_kat_pre_kods as kods, la_kat_pre_razotajs as razotajs, la_kat_pre_kategorija as kategorija, la_kat_pre_nosaukums as nosaukums, la_kat_pre_rup_nr as rup_nr, la_kat_pre_bilde_m as bilde_m, la_kat_pre_bilde_l as bilde_l, la_kat_pre_apraksts as apraksts';
			
			$preces = $sql->retrieve(DB_KATALOGS, '*', 'la_kat_pre_alias = \''.$q.'\' AND la_kat_pre_klase IN ('.USERCLASS_LIST.')', true, null, $this->debug);
			
			$preces = $this->novaktPrefix($preces);
			//$oneDimArr = call_user_func_array('array_merge', $preces); // saplacina no vairākām dimensijām uz vienu
			
			//$this->pDebug($gat);
			//print_r($precess);
			//echo $this->_pre_skaits;
			$this->_preces = $preces;
			return $this->_preces;
		}
	}
	
	
	#	Domāta lai izveido izvadi prieks php, nevis json;
	#	apstrādāPriekšPHP
	#
	
	public function apstradaPP($res){
	//$this->pDebug($res);
		$text = $res[pre];
		foreach($res[preces] as $k){
			foreach($k as $v){
				$text .= $v['sakums'];
				$text .= $v['nosaukums'];
				$text .= $v['kods'];
				$text .= $v['bilde'];
				$text .= $v['razotajs'];
				$text .= $v['kategorija'];
				$text .= $v['rupnr'];
				$text .= $v['apraksts'];
				//$text .= $v['statuss'];
				$text .= $v['beigas'];
				//print_r($value['nosaukums']);
			}
		}
		$text .= $res[post];
		$text .= $res[nav];
		return $text;
	}
	
	
	# sagatavo visas preces
	public function sagVisasP($type){
		$debug = (LA_KATALOGS_DEBUG) ? '<pre class="debug-func">'.__FUNCTION__.',</pre> ' : false;
		echo $debug;
	
		if($this->iegutURL()==""){
			//No variables are specified in the URL.
			//Do stuff accordingly
			//echo "No variables specified in URL...";
			session_unset(); //Free all session variables - jo kataloga sakumlapa
			//echo "gOOOOOOOOOOOOOO!";
		}else{
			//Variables are present. Do stuff:
		}
		//include_once("content/header.php");
		$plain = false;
		//$this->uzstadaSL(0,2); //
		
		//$pnav = $this->pagNav();
		
		$pnav = $this->pagNav();
		$nav = $pnav['pnav'];
		$pos = $pnav['pos'];
		
		$rows = $this->iegutPre();
		//$this->pDebug($rows);
		$nos = LAN_PLUGIN_LA_KAT_PR_I_VISAS.' <div class="pre-skaits">(<div class="pre-skaits-inn">'.$this->skaits().'</div>)</div>';
		$pre = $this->_pre = '<div class="ajax-data"><div class="kat-nosaukums">'.$nos.'</div><div class="gridster"><ul>';
		$post = $this->_post = '</ul></div></div><div class="notirit"></div>';
		$kat = "visas";
		$nosaukums = LAN_PLUGIN_LA_KAT_PR_I_VISAS;
		define("e_PAGETITLE", $nosaukums);
		$res = $this->apstradatPD($rows, $pre, $post, $nav, $kat, $nosaukums, $pos);
		//print_r($res);
		
		if($type=="json"){
			$json = $res;
			return $json;
			//print_r($res);
		}else{
			return $this->apstradaPP($res);
		}
	}
	
	# iegūst preces iekš konkrētas kategorijas
	# TODO vajag apvienot ar iegutVisasP()
	# $kat - datu masīvs, $url_limenis - kura līmenī atrodas kat, $type - text vai json, $relatd - ja velas izvadit lidzigas preces, t.i no šīs pašas kategorijas
	function iegutKatP($kat_arr, $type=null, $related=null){
		$debug = (LA_KATALOGS_DEBUG) ? '<pre class="debug-func">'.__FUNCTION__.',</pre> ' : false;
		echo $debug;
		
		$razotaji = $this->iegutRaz();
		$kategorijas = $this->iegutKat();
		$preces = $this->iegutPre();
		
		$id = $kat_arr[id];
		
		$url_limenis = $kat_arr[url_limenis];
		unset($kat_arr['url_limenis']);
		
		//$this->pDebug($kat_arr);
		
		$kat_arrs = array(0 => $kat_arr); // lai pareizi strādātu novaktPrefix();
		
		$kat_arrs = $this->novaktPrefix($kat_arrs);
		$vkat = $this->apstradaVKD($kat_arrs);
		$vkat = $this->apstradaPPK($vkat);
		
		$kat = $id;
		$this->_vKat = $kat;
		
		//echo "DEBUG";
		$pnav = $this->pagNav('vKat');
		$nav = $pnav['pnav'];
		$pos = $pnav['pos'];
		
		//$nav = $this->pagNav("vKat");
		
		$sql = e107::getDB(); 	// mysql class object
		$sql->db_Set_Charset("utf8");
		
		if($related){
			$kategorija = $kat_arr[0][kategorija];
		
			$nos = LAN_PLUGIN_LA_KAT_VEL_KAT.' '.$this->aNosaukumu($kategorija, $kategorijas, "kat").' <div class="pre-skaits">(<div class="pre-skaits-inn">'.$this->skaits(vKat).'</div>)</div>';
			
			$preces = $sql->retrieve(DB_KATALOGS, '*', 'la_kat_pre_kategorija='.$kategorija.' AND la_kat_pre_klase IN ('.USERCLASS_LIST.') ORDER BY la_kat_pre_'.$this->_order_by.' '.$this->_order.' LIMIT '.$this->_start.', '.$this->_lim.'', TRUE, null, $this->debug);
			
		}else{
			$nos = $this->aNosaukumu($kat, $kategorijas, "kat").' <div class="pre-skaits">(<div class="pre-skaits-inn">'.$this->skaits(vKat).'</div>)</div>';
			
			$preces = $sql->retrieve(DB_KATALOGS, '*', 'la_kat_pre_kategorija='.$kat.' AND la_kat_pre_klase IN ('.USERCLASS_LIST.') ORDER BY la_kat_pre_'.$this->_order_by.' '.$this->_order.' LIMIT '.$this->_start.', '.$this->_lim.'', TRUE, null, $this->debug);
		}
		
		if($preces){
			//$sub_kat = $sub_kat;
			$preces = $this->novaktPrefix($preces);
		}else{
			$preces = array('error' => true, 'code' => $this->errorCode(101));
			//return false;
		}
		
		
		$pre = '<div class="ajax-data"><div class="kat-nosaukums">'.$nos.'</div><div class="gridster">'.$vkat.'<ul>';
		$post = $this->_post = '</ul></div></div><div class="notirit"></div>';
		$nosaukums = LAN_PLUGIN_LA_KAT_PR_I_KATEGORIJA." - ".$nos;
		define("e_PAGETITLE", $nosaukums);
		$res = $this->apstradatPD($preces, $pre, $post, $nav, $kat, $nosaukums, $pos);
	
		//echo $type;
		if($type=="json"){
			$json = $res;
			return $json;
			//print_r($res);
		}else{
			$resp[err] = $res[error];
			$resp[text] = $this->apstradaPP($res);
			//$this->pDebug($resp);
			return $resp;
		}
	}
	
	# metode, kas izvada vienu preci
	# ja $id ir array tad vienkārši sūta uz tālāko apstrādi un neveic pieprasījumu no db
	public function iegutVienuP($id=null,$type=null){
		$debug = (LA_KATALOGS_DEBUG) ? '<pre class="debug-func">'.__FUNCTION__.',</pre> ' : false;
		echo $debug;
		
		$sql = e107::getDB(); 	// mysql class object
		$sql->db_Set_Charset("utf8");
		
		$preces = $this->iegutPre();
		//$this->uzstadaSL(0,2)
		
		///$this->pDebug($id);
		
		$pre = $this->_pre = '<div class="ajax-data"><div class="prec-nosaukums">'.LAN_PLUGIN_LA_KAT_PR_PRECE.'</div><div class="gridster"><ul>';
		$post = $this->_post = '</ul></div></div><div class="notirit"></div>';
		$kat = "";//$this->aNosaukumu($id, $prece);
		
		//echo "DEBUGGGGGG!";
		
		if(is_array($id)){
			$nosaukums = LAN_PLUGIN_LA_KAT_PR_PRECE." - ".$id[0][nosaukums];
			$res = $this->apstradatPD($id, $pre, $post, $nav, $kat, $nosaukums);
		}else{
			$nosaukums = LAN_PLUGIN_LA_KAT_PR_PRECE." - ".$this->aNosaukumu($id, $preces);
			$prece = $sql->retrieve(DB_KATALOGS, '*', 'la_kat_pre_id = '.$id.' AND klase IN ('.USERCLASS_LIST.')', TRUE, null, $this->debug);
			$res = $this->apstradatPD($prece, $pre, $post, $nav, $kat, $nosaukums);
		}
		
		define("e_PAGETITLE", $nosaukums);

		if($type=="json"){
			return $res;
		}else{
			return $this->apstradaPP($res);
		}
	}
	
	
	public function limit_text($text, $limit, $chlim){
		if(strlen($text > $chlim)){
			$text = substr($text, 0, $chlim) . '...';
		}else if(str_word_count($text, 0) > $limit){
			$words = str_word_count($text, 2);
			$pos = array_keys($words);
			$text = substr($text, 0, $pos[$limit]) . '...';
		}
		return $text;
	}

	//echo limit_text('Hello here is a long sentence blah blah blah blah blah hahahaha haha haaaaaa', 5);
	
	
	#	Apastrādā preču datus
	#	metode, kas apstrada datus, un atgriez rezultatu masīvā
	#	$data - masīvs, $pre - html pirms preces, $post - html pec preces, $nav - priekš navigācijas $kat - kad id, $nosaukums - kas būs iekš <title>
	#	$pos - position priekš režģa;
	#
	public function apstradatPD($data=array(), $pre, $post, $nav, $kat, $nosaukums, $pos=array()){
		$debug = (LA_KATALOGS_DEBUG) ? '<pre class="debug-func">'.__FUNCTION__.',</pre> ' : false;
		echo $debug;
	
		$tp = e107::getParser(); 				// parser for converting to HTML and parsing templates etc. 
	
		//echo "<pre>";
		//print_r($data);
		//echo "</pre>";
	
		//$this->_rows = $data;
		//$this->_pre = $pre;
		//$this->_post = $post;
		//$this->_kat = $kat; 
		//$this->_nosaukums = $nosaukums; 

		if(!$data['error']){
		
			$res['pre'] = $pre;
			$res['kategorija'] = $kat;
			$res['nosaukums'] = $nosaukums;
			
			$res['pos'] = $pos;
			
			function prettyPhoto($bildeM,$bildeL,$nosaukums,$alias){
				$prettyPhoto = "<div class='img-gal' style='display: none'><a href='".LA_KAT_BASE.LA_KAT_SEF.'datnes/php/connector.php?cmd=file&target='.$bildeL[0]."' class='pretty-bildites' rel='prettyPhoto[".$alias."]' title='".$nosaukums."'><div class='galvena-bilde'><img src='".LA_KAT_BASE.LA_KAT_SEF.'datnes/php/connector.php?cmd=file&target='.$bildeL[0]."' alt='".$nosaukums."' /></div></a><div class='img-gal-tmbs'><div class='img-gal-tmbs-inner'>";
					foreach($bildeM as $key => $val){
						//echo 'DEBUG!';
						if($key != 0){
							$prettyPhoto .= "<a href='".LA_KAT_BASE.LA_KAT_SEF.'datnes/php/connector.php?cmd=file&target='.$bildeL[$key]."' class='pretty-bildites' rel='prettyPhoto[".$alias."]' title='".$nosaukums."'><img src='".LA_KAT_BASE.LA_KAT_SEF.'images/'.$bildeM[$key]."' width='80' height='80' alt='".$nosaukums."' /></a>";
						}
					}
				$prettyPhoto .= "</div></div></div>";
				return $prettyPhoto;
			}
			
			$i = 0;
			$col = 1;
			foreach($data as $key => $value){
			
				//$this->pDebug($pos);
			
				//$this->pDebug('ROW: '.$pos[pos][$i][row]);
				//$this->pDebug('COL: '. $pos[pos][$i][col]);
			
				$alias = $value['alias'];
			
				$res['preces'][$key]['prece']['id'] = $value['id'];
				//$res['preces'][$key]['prece']['sakums'] =  "<li data-precesid=".$value['id']." class='prece gs_w' data-row=".$pos[pos][$i][row]." data-col=".$pos[pos][$i][col]." data-sizex=".rand(1,1)." data-sizey=".rand(1,1)."><a href='".LA_KAT_BASE.LA_KAT_SEF.'prece/'.$alias."' class='preces-saite js-as'>";
				
				if(!empty($pos)){
					$res['preces'][$key]['prece']['sakums'] =  "<li data-precesid=".$value['id']." data-href='".LA_KAT_BASE.LA_KAT_SEF.'prece/'.$alias."' class='prece gs_w' data-row=".$pos['pos'][$i]['row']." data-col=".$pos['pos'][$i]['col']." data-sizex=".rand(1,1)." data-sizey=".rand(1,1).">";
				}else{
					$res['preces'][$key]['prece']['sakums'] =  "<li data-precesid=".$value['id']." data-href='".LA_KAT_BASE.LA_KAT_SEF.'prece/'.$alias."' class='prece gs_w' data-row='1' data-col='".$col."' data-sizex=".rand(1,1)." data-sizey=".rand(1,1).">";
					if($col==5){
						$col=1;
					}
				}
				
				
			
				$bildeL =  $value['bilde_l'];
				$bildeM =  $value['bilde_m'];
				
				$bildeM = explode(",", $bildeM);
				$bildeL = explode(",", $bildeL);
				
				//$this->pDebug( prettyPhoto($bildeM,$bildeL) );
				
				$nosaukums = $tp->toHTML($value['nosaukums'], TRUE, 'TITLE');
				
				$nosaukums_lim = $this->limit_text($nosaukums, 2, 60);
				
				$res['preces'][$key]['prece']['nosaukums'] =  "<div class='preces-augsa'><div class='preces-nosaukums' data-nosaukums='".$nosaukums."'><span>".LAN_PLUGIN_LA_KAT_PR_I_NOSAUKUMS."</span> ".$nosaukums_lim."<br /></div>";

				$res['preces'][$key]['prece']['kods'] =  "<div class='preces-kods'><span>".LAN_PLUGIN_LA_KAT_PR_I_KODS."</span> ".$tp->toHTML($value['kods'], TRUE, 'TITLE')."</div></div>";
			
				$res['preces'][$key]['prece']['bilde'] .=  "<div class='preces-bilde' data-href='".LA_KAT_BASE.LA_KAT_SEF.'datnes/php/connector.php?cmd=file&target='.$bildeL[0]."'><img src='".LA_KAT_BASE.LA_KAT_SEF.'images/'.$bildeM[0]."'/><br />".prettyPhoto($bildeM,$bildeL,$nosaukums,$alias)."</div>";
				
				$res['preces'][$key]['prece']['razotajs'] =  "<div class='prece'><div class='preces-razotajs'><span>".LAN_PLUGIN_LA_KAT_PR_I_RAZOTAJS."</span> ".$tp->toHTML($this->aNosaukumu($value['razotajs'], $this->iegutRaz(), 'raz'), TRUE, 'TITLE')."<br /></div>";
			
				$res['preces'][$key]['prece']['kategorija'] =  "<div class='preces-kat'><span>".LAN_PLUGIN_LA_KAT_PR_I_KATEGORIJA."</span> ".$tp->toHTML($this->aNosaukumu($value['kategorija'], $this->iegutKat(), 'kat'), TRUE, 'TITLE')."<br /></div>";
			
				$res['preces'][$key]['prece']['rupnr'] =  "<div class='preces-rup-nr'><span>".LAN_PLUGIN_LA_KAT_PR_I_RUPNICAS_NUMURS."</span> ".$tp->toHTML($value['rup_nr'], TRUE, 'TITLE')."<br /></div>";
			
				$apraksts = $value['apraksts'];
				
				$apraksts = preg_replace("#\[\b(html)\]#","",$apraksts); //atrod ar regex tieši [html] un izvāc ārā
				$apraksts = preg_replace("#\[\/\b(html)\]#","",$apraksts); //atrod ar regex tieši [/html] un izvāc ārā
				
				$res['preces'][$key]['prece']['apraksts'] =  "<div class='preces-apraksts'>".$tp->toHTML($apraksts, TRUE, 'BODY')."<br /></div>";
			
				$noliktava = $value['noliktava'];
			
				if($noliktava == 1){
					$noliktava = LAN_PLUGIN_LA_KAT_PR_I_STATUSS_JA;
				}else{
					$noliktava = LAN_PLUGIN_LA_KAT_PR_I_STATUSS_NE;
				}
			
				$res['preces'][$key]['prece']['statuss'] =  "<div class='preces-statuss'><span>".LAN_PLUGIN_LA_KAT_PR_I_STATUSS."</span> ".$tp->toHTML($noliktava, TRUE, 'TITLE')."<br /></div>";
			
				//$res['preces'][$key]['prece']['beigas'] =  "</div></a></li>";
				$res['preces'][$key]['prece']['beigas'] =  "</div></li>";
				
				$i++;
			}
		
			$res['nav'] = $nav;
			$res['post'] = $post;
			
			$res['breadcrumb'] = $this->breadCrumb(LA_KAT_BREADC_SEP);
		
			//array_push($res1, $res);
			//echo json_encode($res);
			return $res;
			//print_r($res);
		}else{
			
			//$res['pre'] = '<div class="ajax-data alert-error"><h1 class="nav-dati">'.LAN_PLUGIN_LA_KAT_ERROR.'</h1>';
			//$res['post'] = '</div>';
			//$res['kluda'] = 1;
			//return $res;
			
			$res['pre'] = '<div class="alert-error kat-error"><h1>'.LAN_PLUGIN_LA_KAT_ERROR.'</hi>';
			$res['post'] =  "</div>";
			$res['error'] = $data;
			//http_response_code(404);
			return $res;
			
			//header('Content-Type: text/plain');
			//exit('404 Not Found');
		}
	}
	

	# /viss priekš precēm
	
	
	
	// iegūst kopējo skaitu
	# $kam - precēm, kategorijām, ražotājiem, pre, kat, raz. default pre
	# $q - neobligāts query
	# $force - vienalga vai ir jau kaut kas, iekšā masīvā, vai nav, tiks veikts pieprasījums no db
	public function skaits($kam="Pre", $q=null, $force=true){
		$debug = (LA_KATALOGS_DEBUG) ? '<pre class="debug-func">'.__FUNCTION__.',</pre> ' : false;
		echo $debug;
	
		switch($kam){
			case "Pre":
			if(!empty($this->_pre_skaits) || $force == false){
				//echo "šis ir false";
				return $this->_pre_skaits;
				
			}else{
				//echo "šis ir true";
				$sql = e107::getDB(); 	// mysql class object
				$this->_pre_skaits = $sql->db_Count(DB_KATALOGS, '(*)', 'la_kat_pre_klase IN ('.USERCLASS_LIST.')');
				return $this->_pre_skaits;
			}
			break;
			case "Kat":
			if(!empty($this->_kat_skaits) || $force == false){
				//echo "šis ir false";
				return $this->_kat_skaits;
				
			}else{
				//echo "šis ir true";
				$sql = e107::getDB(); 	// mysql class object
				$this->_kat_skaits = $sql->db_Count(DB_KATEGORIJAS, '(*)', 'la_kat_kat_klase IN ('.USERCLASS_LIST.')');
				return $this->_kat_skaits;
			}
			break;
			case "pKat": //parent kategorijām
			if(!empty($this->_pkat_skaits) || $force == false){
				//echo "šis ir false";
				return $this->_pkat_skaits;
				
			}else{
				//echo "šis ir true";
				$sql = e107::getDB(); 	// mysql class object
				$this->_pkat_skaits = $sql->db_Count(DB_KATEGORIJAS, '(*)', 'la_kat_kat_parent=0 AND la_kat_kat_klase IN ('.USERCLASS_LIST.')');
				return $this->_pkat_skaits;
			}
			break;
			case "sKat": //sub kategorijām
			if(!empty($this->_skat_skaits) || $force == false){
				//echo "šis ir false";
				return $this->_skat_skaits;
				
			}else{
				//echo "šis ir true";
				$sql = e107::getDB(); 	// mysql class object
				$this->_skat_skaits = $sql->db_Count(DB_KATEGORIJAS, '(*)', 'la_kat_kat_parent='.$this->_sKat.' AND la_kat_kat_klase IN ('.USERCLASS_LIST.')');
				return $this->_skat_skaits;
			}
			break;
			case "Raz":
			if(!empty($this->_raz_skaits) || $force == false){
				//echo "šis ir false";
				return $this->_raz_skaits;
				
			}else{
				//echo "šis ir true";
				$sql = e107::getDB(); 	// mysql class object
				$this->_raz_skaits = $sql->db_Count(DB_RAZOTAJI, '(*)', 'la_kat_raz_klase IN ('.USERCLASS_LIST.')');
				return $this->_raz_skaits;
			}
			case "vKat": // vienai kategorijai
			if(!empty($this->_vkat_skaits) || $force == false){
				//echo "šis ir false";
				return $this->_vkat_skaits;
				
			}else{
				//echo "šis ir true";
				$sql = e107::getDB(); 	// mysql class object
				$this->_vkat_skaits = $sql->db_Count(DB_KATALOGS, '(*)', 'la_kat_pre_kategorija='.$this->_vKat.' AND la_kat_pre_klase IN ('.USERCLASS_LIST.')');
				return $this->_vkat_skaits;
			}
			break;
			case "mSk": // meklēšanas rezultātiem
			if(!empty($this->_mSk_skaits) || $force == false){
				//echo "šis ir false";
				return $this->_mSk_skaits;
				
			}else{
				//echo "šis ir true";
				$sql = e107::getDB(); 	// mysql class object
				$this->_mSk_skaits = $sql->db_Count(DB_KATALOGS, '(*)', $q.' AND la_kat_pre_klase IN ('.USERCLASS_LIST.')');
				return $this->_mSk_skaits;
			}
			break;
			default:
		}
	}
	
	// iegūst razotajus
	public function iegutRaz($force=false){
		$debug = (LA_KATALOGS_DEBUG) ? '<pre class="debug-func">'.__FUNCTION__.',</pre> ' : false;
		echo $debug;
		
		//$this->_razotaji = "";
		//$this->pDebug($_SESSION['razotaji']);
		if($force || empty($_SESSION['razotaji'])){
			unset($_SESSION['razotaji']);
			$sql = e107::getDB(); 	// mysql class object
			$sql->db_Set_Charset("utf8");
			// combined select and fetch function - returns an array. 
			//$razotaji = $sql->retrieve(DB_RAZOTAJI, '*', 'la_kat_raz_klase IN ('.USERCLASS_LIST.') ORDER BY la_kat_raz_'.$this->_order_by.' '.$this->_order.' LIMIT '.$this->_start.', '.$this->_lim.'', true, null, $this->debug);
			$razotaji = $sql->retrieve(DB_RAZOTAJI, '*', 'la_kat_raz_klase IN ('.USERCLASS_LIST.')', true, null, $this->debug);
			$razotaji = $this->novaktPrefix($razotaji);
			$_SESSION['razotaji'] = $razotaji; // store session data
			//$this->_razotaji = $razotaji;
			//return $this->_razotaji;
			return $_SESSION['razotaji'];
		}else{
			//echo 222;
			return $_SESSION['razotaji'];
		}
	}
	
	// iegūst kategorijas
	/*public function iegutKat($force=false){
		$debug = (LA_KATALOGS_DEBUG) ? '<pre class="debug-func">'.__FUNCTION__.',</pre> ' : false;
		echo $debug;
	
		//$this->_razotaji = "";
		//$this->pDebug($_SESSION['razotaji']);
		if($force || empty($_SESSION['kategorijas'])){
			unset($_SESSION['kategorijas']);
			$sql = e107::getDB(); 	// mysql class object
			$sql->db_Set_Charset("utf8");
			// combined select and fetch function - returns an array. 
			//$razotaji = $sql->retrieve(DB_RAZOTAJI, '*', 'la_kat_raz_klase IN ('.USERCLASS_LIST.') ORDER BY la_kat_raz_'.$this->_order_by.' '.$this->_order.' LIMIT '.$this->_start.', '.$this->_lim.'', true, null, $this->debug);
			$kategorijas = $sql->retrieve(DB_KATEGORIJAS, '*', 'la_kat_kat_klase IN ('.USERCLASS_LIST.')', true, null, $this->debug);
			$kategorijas = $this->novaktPrefix($kategorijas);
			$_SESSION['kategorijas'] = $kategorijas; // store session data
			//$this->_razotaji = $razotaji;
			//return $this->_razotaji;
			return $_SESSION['kategorijas'];
		}else{
			//echo 222;
			return $_SESSION['kategorijas'];
		}
	}*/
	
	// iegūst visas parent kategorijas
	public function iegutVPKat($force=false){
		$debug = (LA_KATALOGS_DEBUG) ? '<pre class="debug-func">'.__FUNCTION__.',</pre> ' : false;
		echo $debug;
		
		if(!empty($this->_kategorijas) || $force != false){
			return $this->_kategorijas;
		}else{
		$sql = e107::getDB(); 	// mysql class object
		$sql->db_Set_Charset("utf8");
		// combined select and fetch function - returns an array. 
		$kategorijas = $sql->retrieve(DB_KATEGORIJAS, '*', 'la_kat_kat_parent = 0 AND la_kat_kat_klase IN ('.USERCLASS_LIST.') ORDER BY la_kat_kat_'.$this->_order_by.' '.$this->_order.' LIMIT '.$this->_start.', '.$this->_lim.'', true, null, $this->debug);
		
		// sagatavo datu izvadei, novācot la_kat_kat_
		$kategorijas = $this->novaktPrefix($kategorijas);

		//$this->pDebug($kategorijas);
		
			$this->_kategorijas = $kategorijas;
			return $this->_kategorijas;
		}
	}
	
	// iegūst plugin iestatījumus
	public function iegutIest(){
		if($iestatijumi = e107::getPlugPref(LA_KAT)){
			return $iestatijumi;
		}
	}
	
	// atrod nosaukumu razotajam, kategorijām
	public function aNosaukumu($id, $masivs, $kam=null){
		switch($kam){
			case "raz":
				foreach($masivs as $key=>$value){
					if($value['id'] == $id){
						return $value['nosaukums'];
					}
				}
			break;
			case "kat":
				foreach($masivs as $key=>$value){
					if($value['id'] == $id){
						return $value['nosaukums'];
					}
				}
			break;
			default:
				foreach($masivs as $key=>$value){
					if($value['id'] == $id){
						return $value['nosaukums'];
					}
				}
		}
	}
	
	// meklē masīvā, $ret - true atgriez id
	public function mekleM($array, $key, $value, $ret=false){
		$results = array();
		if(is_array($array)){
			if(isset($array[$key]) && $array[$key] == $value){
				$results[] = $array;
			}
			foreach($array as $subarray){
				$results = array_merge($results, $this->mekleM($subarray, $key, $value));
			}
		}
		if($ret==true){
			return $results[0]['id']; // iegūst id
		}else{
			return $results;
		}
	}
	
	# maza metode, lai meklētu iekš url masīva
	public function mekU($str){
		$url = $this->iegutURL(3); // iegust visu url masīvu
		$res = array();
		$s = array_search($str, $url, true);
		//$this->pDebug($url);
		if($s!==false){
			//echo 'izpildās!';
			$res[key] = $s;
			$res[res] = true;
			//$this->pDebug($res);
			return $res;
			//return $true;
		}
		return false;
	}
	

	# parbauda vai visas kategorijas un vai iekš adreses nav kategorijas, ja ne atgriez konkretas kategorijas objektu
	# $q - meklē iekš kopējā url masīva
	public function parbauditKat($q='kategorija'){
		$debug = (LA_KATALOGS_DEBUG) ? '<pre class="debug-func">'.__FUNCTION__.',</pre> ' : false;
		echo $debug;
		
		if(isset($q) && $q !== 'kategorija'){
			//echo 'DEBUG!!! - Atradu!!!';
			//$this->pDebug($q);
			$m = $this->mekU($q);
			if($m[res]){
				return $m;
			}
		}else{
		
			$url = $this->iegutURL(3); // iegust visu url masīvu
			$sk = count($url); // cik elementi masīvā
			
			//echo 'KATEGORIJA ';
			//$this->pDebug(end($url));
			
			if($id = $this->mekU('kategorija')){ // meklē vai iekš adreses ir kategorija
				//echo 'KATEGORIJA ';
				if($kat = $this->mekU('visas')){ // meklē vai iekš adreses ir visas
					$katres = 'visas';
					return $katres;
				}else{ // ja nav visas
					//echo $this->iegutURL(2);
					//$this->pDebug($id);
					
					//$this->uzstadaSL(0,100); // lai meklē visās kategorijās
					if($kat = $this->iegutKat()){
						//echo "DEBUG ".$id+1;
						
						//$i = count($url);
						$i = $sk - $id[key];
						
						foreach($url as $u){
							if($res = $this->mekleM($kat, 'alias', $url[$id[key]+$i])){
								//$this->pDebug($i.' līmenis ');
								break;
							}
							$i--;
						}
						
						$oneDimArr = call_user_func_array('array_merge', $res); // saplacina no vairākām dimensijām uz vienu
						$oneDimArr['url_limenis'] = $i; // pievieno masīvam kurā url līmenī tika atrast
						//$this->pDebug($oneDimArr);
						return $oneDimArr;
						
							//$res[id] = $res[0]['la_kat_kat_id']; // iegūst id
							//$res[parent_id] = $res[0]['la_kat_kat_parent']; // iegūst parent id
							//echo "DEBUG";
							//$this->pDebug($id);
					}
				}
			}else if(end($url) == "" || $url[$sk-2] == 'lapa' || end($url) == "katalogs"){
				//echo 'sākums';
				return 'sakums';
			}
		
		}
		
	}
	

	#	$q - search query
	#	$type - text,json
	#
	public function mekletP($q, $type){
		$sql = e107::getDB(); 	// mysql class object
		$sql->db_Set_Charset("utf8");
		
		$mekmas = array('la_kat_pre_kods','la_kat_pre_nosaukums','la_kat_pre_rup_nr','la_kat_pre_apraksts');
		$len = count($mekmas);
		$i=0;
		
		//$sql = "SELECT * FROM `edgars_la_kat_preces` WHERE `nosaukums` LIKE \'%prece%\' LIMIT 0, 30 ";
		//CONVERT(p.NAME USING utf8) LIKE _utf8 '%jose%' COLLATE utf8_general_ci;
		foreach($mekmas as $k=>$v){
			if($i==0){
			// first
			}else if ($i == $len - 1) {
			// last
				$txt .= $v." LIKE '%".$q."%' ";
			}else{
				$txt .= $v." LIKE '%".$q."%' OR ";
			}
			$i++;
		}
		
		$skaits = $this->skaits("mSk", $txt);
		//$this->pDebug($skaits);
		$pnav = $this->pagNav('mSk', $txt);
		$nav = $pnav['pnav'];
		$pos = $pnav['pos'];
		//$this->pDebug($nav);
		
		$preces = $sql->retrieve(DB_KATALOGS, '*', ''.$txt.' AND la_kat_pre_klase IN ('.USERCLASS_LIST.') LIMIT '.$this->_start.', '.$this->_lim.'', TRUE, null, $this->debug);
		$rezsk = $skaits;
		if($rezsk > 1){
		//atradu
			$rezsk = $rezsk;
			$rezsakums = LAN_PLUGIN_LA_KAT_MEK_REZ_PRE;
			$rezbeigas = LAN_PLUGIN_LA_KAT_MEK_REZ_POST;
		}else if($rezsk == 1){
		//atradu tikai vienu
			$rezsk = $rezsk;
			$rezsakums = LAN_PLUGIN_LA_KAT_MEK_REZ_PRE;
			$rezbeigas = LAN_PLUGIN_LA_KAT_MEK_REZ_POST_S;
		}else{
		//neatradu
			$rezsk = "";
			$rezsakums = LAN_PLUGIN_LA_KAT_MEK_REZ_PRE_E;
			$rezsakums = LAN_PLUGIN_LA_KAT_MEK_REZ_PRE_E;
		}
		
		
		$preces = $this->novaktPrefix($preces);
		
		$pre = $this->_pre = '<div class="ajax-data"><div class="prec-nosaukums alert-success">'.$rezsakums.' '.$rezsk.' '.$rezbeigas.'</div><div class="gridster"><ul>';
		$post = $this->_post = '</ul></div></div><div class="notirit"></div>';
		$kat = LAN_PLUGIN_LA_KAT_MEK_REZ;
		$nosaukums = $rezsakums.' '.$rezsk.' '.$rezbeigas;
		$res = $this->apstradatPD($preces, $pre, $post, $nav, $kat, $nosaukums, $pos);
		
		//$this->pDebug($res);
		
		if($type=="json"){
			//$this->pDebug($res);
			return $res;
			
		}else{
			return $this->apstradaPP($res);
		}
	}
	
	
	# navigācija
	
	
	public function LAmenuLoop($array = array(), $parent_id = 0){
	
		if(!empty($array[$parent_id])){
			
			$res = '<ul class="kategorijas nav nav-pills">';
			foreach($array[$parent_id] as $items){
				$res .= '<li data-laid="'.$items[id].'">'.$items[nosaukums].'<li>';
			}
			$res = '</ul>';
			
		}

	}
	
	
	
	
	# funkcija lai izvada, next prev pogas, rekina cik lapas būs
	# $type kam atgriezt rezultatu, visam precēm, kat, raz, vienai precei, def visam
	# $q - optional query
	public function pagNav($type="Pre", $q=null){
		$debug = (LA_KATALOGS_DEBUG) ? '<pre class="debug-func">'.__FUNCTION__.',</pre> ' : false;
		echo $debug;

		$url = $this->iegutURL(3);
		$lapa = $this->mekU('lapa');

		if($lapa && $url[$lapa[key]+1]){
			$page = $url[$lapa[key]+1];
		//echo $this->iegutURL();
		// Get the current page or set default if not given
		}else{
			$page = 1;
		}
		
		//echo $page;
		$page2 = $page - 1; // lai reizinājums butu pareizs jāatņem 1, tākā sākas ar nulli, tad ja lapa viens, tad serverim padod ka tomēr nulle, jo reizinot ar nulli ir nulle
		
		$start = $page2 * $this->_lim;
		
		//$this->_skaits = 57;
		
		switch($type){
			case "Pre":
			$pages_count = ceil($this->skaits() / $this->_lim);
			break;
			case "Kat":
			$pages_count = ceil($this->skaits("Kat") / $this->_lim);
			break;
			case "Raz":
			$pages_count = ceil($this->skaits("Raz") / $this->_lim);
			break;
			case "vPrece":
			$pages_count = ceil($this->skaits("vPrece") / $this->_lim);
			break;
			case "vKat":
			$pages_count = ceil($this->skaits("vKat") / $this->_lim);
			break;
			case "pKat":
			$pages_count = ceil($this->skaits("pKat") / $this->_lim);
			break;
			case "sKat":
			$pages_count = ceil($this->skaits("sKat") / $this->_lim);
			break;
			case "mSk":
			$pages_count = ceil($this->skaits("mSk", $q) / $this->_lim);
			break;
			default:
		}
		
		//echo $this->_skaits;
		
		$is_first = $page == 1;
		$is_last  = $page == $pages_count;
		
		// Prev cannot be less than one
		$prev = max(1, $page - 1);
		// Next cannot be larger than $pages_count
		$next = min($pages_count, $page + 1);
		
		if($pages_count>1){
		
		$urlpre = $this->iegutURL();
		if($urlpre!=""){
			$urlpre = $this->iegutURL().'/';
		}
		
		$nav_pre = '<div class="pag-navigation"><div class="inner-pag-nav">';
		
		// If we are on page 2 or higher
		if(!$is_first){
			$first = '<a class="btn pirma-lapa" href="../">'.LAN_PLUGIN_LA_KAT_LAPA_FIRST.'</a>';
			$first .= '<a class="btn iep-lapa" href="../lapa/'.$prev.'">'.LAN_PLUGIN_LA_KAT_LAPA_PREV.'</a>';
		}else{
			$first = '<a class="btn" disabled="disabled">'.LAN_PLUGIN_LA_KAT_LAPA_FIRST.'</a>';
			$first .= '<a class="btn" disabled="disabled">'.LAN_PLUGIN_LA_KAT_LAPA_PREV.'</a>';
			
			if($this->iegutURL() == 1){
				$last = '<a class="btn nak-lapa" href="../lapa/'.$next.'">'.LAN_PLUGIN_LA_KAT_LAPA_NEXT.'</a>';
				$last .= '<a class="btn ped-lapa" href="../lapa/'.$pages_count.'">'.LAN_PLUGIN_LA_KAT_LAPA_LAST.'</a>';
			}else{
				$last = '<a class="btn nak-lapa" href="'.$urlpre.'lapa/'.$next.'">'.LAN_PLUGIN_LA_KAT_LAPA_NEXT.'</a>';
				$last .= '<a class="btn ped-lapa" href="'.$urlpre.'lapa/'.$pages_count.'">'.LAN_PLUGIN_LA_KAT_LAPA_LAST.'</a>';
			}
			
		}
		
		$mid = '<div class="tagad-lapa">'.$page.'</div> / <div class="kopa-lapas">'.$pages_count.'</div>';
		
		// If we are not at the last page
		if(!$is_last && $is_first<1){
			$last = '<a class="btn nak-lapa" href="../lapa/'.$next.'">'.LAN_PLUGIN_LA_KAT_LAPA_NEXT.'</a>';
			$last .= '<a class="btn ped-lapa" href="../lapa/'.$pages_count.'">'.LAN_PLUGIN_LA_KAT_LAPA_LAST.'</a>';
		}
		
		if($is_last){
			$last = '<a class="btn" disabled="disabled">'.LAN_PLUGIN_LA_KAT_LAPA_NEXT.'</a>';
			$last .= '<a class="btn" disabled="disabled">'.LAN_PLUGIN_LA_KAT_LAPA_LAST.'</a>';
		}
		
		
		
		
		$nav_post = '</div></div>';
		
		$this->uzstadaSL($start,$this->_lim);
		
		//izrekinatPos($w=1000, $pw=186, $ph=186, $inp=10, $page=1, $cnt=10);
		
		$pos = $this->izrekinatPos(1000, 186, 186, $this->_lim, $page, 10);
		
		$res[pnav] = $nav_pre.$first.$mid.$last.$nav_post;
		$res[pos] = $pos;
		
		return $res;
		
		}else{
			return '';
		}
		
		
	}
	
	# /navigācija
	

}






/*
class testklasevajagsaprast extends eControllerFront
{
	#
	#* Plugin name - used to check if plugin is installed
	#* Set this only if plugin requires installation
	#* @var string
	 
	protected $plugin = 'la_katalogs';

	
	 #* User input filter (_GET)
	 #* Format 'action' => array(var => validationArray)
	 #* @var array
	 
	
	protected $filter = array(
		'all' => array(
			'category' => array('int', '0:'),
			'tag' => array('str', '2:'),
		),
	);
	

	public function init()
	{
		//e107::lan('faqs', 'front');
		//e107::css('faqs','faqs.css');
		//$this->setResponse("eResponse");
		//$this->addTitle("test");
		
	}

	public function actionIndex()
	{
		$this->_forward('all');
		
	}

	public function actionAll()
	{
		
		$sql = e107::getDb();
		$sql->db_Set_Charset("utf8");
		$tp = e107::getParser();

		//global $FAQ_START, $FAQ_END, $FAQ_LISTALL_START,$FAQ_LISTALL_LOOP,$FAQ_LISTALL_END;

		$FAQ_START = e107::getTemplate('faqs', true, 'start');
		$FAQ_END = e107::getTemplate('faqs', true, 'end');
		$FAQ_LISTALL = e107::getTemplate('faqs', true, 'all');
		
		
		// request parameter based on filter (int match in this case, see $this->filter[all][category]) - SAFE to be used in a query
		$category = $this->getRequest()->getRequestParam('category');
		$where = array();
		if($category)
		{
			$where[] = "f.faq_parent={$category}";
		}
		$tag = $this->getRequest()->getRequestParam('tag');
		if($tag)
		{
			$where[] = "FIND_IN_SET ('".$tp->toDB($tag)."', f.faq_tags)";
		}

		if($where)
		{
			$where = ' AND '.implode(' AND ' , $where);
		}
		else $where = '';

		$query = "
			SELECT f.*,cat.* FROM #faqs AS f 
			LEFT JOIN #faqs_info AS cat ON f.faq_parent = cat.faq_info_id 
			WHERE cat.faq_info_class IN (".USERCLASS_LIST."){$where} ORDER BY cat.faq_info_order,f.faq_order ";
		$sql->gen($query, false);

		$prevcat = "";
		$sc = e107::getScBatch('faqs', true);
		$sc->counter = 1;
		$sc->tag = htmlspecialchars($tag, ENT_QUOTES, 'utf-8');
		$sc->category = $category;

		$text = $tp->parseTemplate($FAQ_START, true, $sc);

		while ($rw = $sql->db_Fetch())
		{
			$sc->setVars($rw);	

			if($rw['faq_info_order'] != $prevcat)
			{
				if($prevcat !='')
				{
					$text .= $tp->parseTemplate($FAQ_LISTALL['end'], true, $sc);
				}
				$text .= "\n\n<!-- FAQ Start ".$rw['faq_info_order']."-->\n\n";
				$text .= $tp->parseTemplate($FAQ_LISTALL['start'], true, $sc);
				$start = TRUE;
			}

			$text .= $tp->parseTemplate($FAQ_LISTALL['item'], true, $sc);
			$prevcat = $rw['faq_info_order'];
			$sc->counter++;
			if($category) $meta = $rw;
		}
		$text .= $tp->parseTemplate($FAQ_LISTALL['end'], true, $sc);
		$text .= $tp->parseTemplate($FAQ_END, true, $sc);

		// add meta data if there is parent category
		if(!empty($meta))
		{
			$response = $this->getResponse();
			if($meta['faq_info_metad'])
			{
				$response->addMetaDescription($meta['faq_info_metad']);
			}
			if($meta['faq_info_metak'])
			{
				$response->addMetaKeywords($meta['faq_info_metak']);
			}
		}

		$this->addTitle(LAN_PLUGIN_FAQS_FRONT_NAME);

		$this->addBody($text);
		
	}

	
}

*/