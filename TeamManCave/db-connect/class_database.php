<?PHP

class DATABASE{
	
	public $conType = "";
	public $settings = array();
	public $site = array();
	public $user = array();
	public $page = array();
	public $affectedRows;
	public $db_link;
	
	function __construct( ) 
	{
		$CONFIG = array(
			"local"=>array(
				"dbname"=>"teammancave",
				"dbuser"=>"root",
				"dbpass"=>"",
				"server"=>"localhost"
			),
			"server"=>array(
				"dbname"=>"blottcom_teammancave",
				"dbuser"=>"blottcom_blott",
				"dbpass"=>"#%#$234354dfgde345345",
				"server"=>"localhost"
			)
		);	

		$whitelist = array( '127.0.0.1', '::1', 'localhost' );
		
		if( in_array( $_SERVER['REMOTE_ADDR'], $whitelist ) )
		{
			
			$details = $CONFIG['local'];
			
		} else {
			
			$details = $CONFIG['server'];
			
		}
		
		$this->settings['dbname'] = $details['dbname'];
		
		$this->settings['dbuser'] = $details['dbuser'];
		
		$this->settings['dbpass'] = $details['dbpass'];
		
		$this->settings['server'] = $details['server'];
		
	}
	
	function connect()
	{
		$dbname = $this->settings['dbname'];
		$dbuser = $this->settings['dbuser'];
		$dbpass = $this->settings['dbpass'];
		$server = $this->settings['server'];
			
		if( $dbname == "" ) return;

		switch ($this->conType) 
		{
			case "PDO":
				// PDO
				$this->db_link = new PDO("mysql:host=$server;dbname=$dbname", $dbuser, $dbpass ) ;
				break;
			case "mysql":
				// old - depreciated
				$this->db_link = mysql_connect( $server, $dbuser, $dbpass, 1 );
				break;
			case "mysqli_obj":
				// mysqli, object oriented way
				$this->db_link = new mysqli($server,$dbuser,$dbpass,$dbname);
				break;
			default:
				// mysqli, procedural way --- prefered
				$this->db_link = mysqli_connect($server,$dbuser,$dbpass,$dbname);
				break;
 
 
		}
		
		if( mysqli_connect_errno() )
		{
			return "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		
		return $this->db_link;
	}
	
	function queryFetchMulti( $query, $ASSOC = true ) 
	{
		$result = $this->query( $query, $this->db_link );
		if( $result )
		{
			while ( $DATA[] = $this->fetch_array( $result ) );
			
			foreach ( $DATA as $k => $v ) 
			{
				if( is_array( $v ) ) 
				{
					foreach( $v as $idx=>$value ) 
					{
						if( $ASSOC ) 
						{
							$TAB[$k][$this->db2field( $idx )]=$value;
						} else {
							$TAB[$k][]=$value;
						}
					}
				}
			}

			if( isset( $TAB ) ) 
			{
				return ( $TAB );
			}else{
				return 0;
			}
		}
	}
	function queryFetch( $query, $FETCH_ASSOC = true ) 
	{
		$TAB = array();
		$res = $this->query( $query );
		
		if ( $res ) 
		{
			$DATA = $this->fetch_array( $res );
			
			if( is_array( $DATA ) ) 
			{
				
				foreach ( $DATA as $idx => $value ) 
				{
					if( $FETCH_ASSOC ) 
					{
						$TAB[ $this->db2field( $idx ) ] = $value;
					} else {
						$TAB[] = $value;
					}
				}
				return ( $TAB );
			}
		 }
	}
	
	function query( $query ) 
	{
		$res = @mysqli_query( $this->db_link, $query );
		if( $res ) $this->affectedRows = mysqli_affected_rows( $this->db_link );
 
		return $res;
	}
	
	function queryID($query)
	{
		if(empty($this->settings['dbname']))
		{
			return false;
		}
		$res = $this->query($query);
		$myid = mysqli_insert_id($this->db_link);
		return $myid;
	}
	
	function fetch_array( $qs ) 
	{
		return mysqli_fetch_array( $qs, MYSQLI_ASSOC );
	}
	
	function db2Field( $string )
	{
		$string = str_replace( " ", "_", $string );
		$string = strtolower( $string );
		return $string;
	}
}
?>