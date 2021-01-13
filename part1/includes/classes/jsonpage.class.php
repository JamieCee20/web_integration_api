<?php

/**
 * Creates a JSON page based on the parameters
 * 
 * @author Jamie Collins
 * 
 * 
 */
class JSONpage
{
	private $page;
	private $recordset;

	/**
	 * @param $pathArr - an array containing the route information
	 */
	public function __construct($pathArr, $recordset)
	{
		$this->recordset = $recordset;
		$path = (empty($pathArr[1])) ? "api" : $pathArr[1];

		switch ($path) {
			case 'api':
				$this->page = $this->json_welcome();
				break;
			case 'authors':
				$this->page = $this->json_authors();
				break;
			case 'content':
				$this->page = $this->json_content();
				break;
			case 'content_authors':
				$this->page = $this->json_content_authors();
				break;
			case 'rooms':
				$this->page = $this->json_rooms();
				break;
			case 'sessionTypes':
				$this->page = $this->json_sessionTypes();
				break;
			case 'sessions':
				$this->page = $this->json_sessions();
				break;
			case 'sessionChair':
				$this->page = $this->json_sessionChair();
				break;
			case 'slots':
				$this->page = $this->json_slots();
				break;
			case 'login':
				$this->page = $this->json_login();
				break;
			case 'update':
				$this->page = $this->json_update();
				break;
			default:
				$this->page = $this->defaultMessage();
				break;
		}
	}


	/**
	 * @param - Santise entered value
	 * @return - the value that has been sanitised
	 */
	private function sanitiseNum($x)
	{
		return filter_var($x, FILTER_VALIDATE_INT, array("options" => array("min_range" => 0, "max_range" => 1000)));
	}

	/**
	 * function to return default welcome message when searching api in url.
	 * @return $msg set as array of data.
	 */
	private function json_welcome()
	{
		$msg = array("message" => "welcome to the api: Endpoints are found on the documentation page.", "author" => "Jamie Collins", "status" => 200);
		return json_encode($msg);
	}

	/**
	 * custom function that returns all data related to the authors table
	 * @return - record set data via query string
	 */
	private function json_authors()
	{
		$query  = "SELECT authors.authorId, authors.name FROM authors ";
		$params = [];
		$where = " WHERE ";
		$doneWhere = FALSE;

		/**
		 * @param - Adds a search parameter of a name
		 */
		if (isset($_REQUEST['search'])) {
			$where .= " name LIKE :initial";
			$doneWhere = TRUE;
			$params["initial"] = $_REQUEST['search'];
		}

		/**
		 * @param - Search for specific contentId
		 */
		if(isset($_REQUEST['contentId'])) {
			$query .= " INNER JOIN content_authors on
			(authors.authorId = content_authors.authorId)
			INNER JOIN content on
			(content_authors.contentId = content.contentId)
			";
	   
			$where .= " content.contentId = :contentId ";
			$doneWhere = TRUE;
			$params["contentId"] = $_REQUEST['contentId'];
		}

		/**
		 * @param - Entered ID to find matching authorId
		 */
		if (isset($_REQUEST['id'])) {
			$query .= " WHERE authorId = :id";
			$params = ["id" => $_REQUEST['id']];
		}

		/**
		 * @param - Limit authors on the page with entered integer
		 */
		if (isset($_REQUEST['limit'])) {
			$query .= " LIMIT :limit";
			$params = ["limit" => $_REQUEST['limit']];
		}

		/**
		 * @param - Limit authors of 10 per page and specify page number with integer
		 */
		if (isset($_REQUEST['page'])) {
			$query .= " ORDER BY name";
			$query .= " LIMIT 10 ";
			$query .= "OFFSET ";
			$query .= 10 * ($this->sanitiseNum($_REQUEST['page']) - 1);
		}

		$query .= $doneWhere ? $where : "";
		$nextpage = null;
		$res = json_decode($this->recordset->getJSONRecordSet($query, $params),true);
		
		$res['status'] = 200;
		$res['message'] = "ok";
		$res['next_page'] = $nextpage;
		return json_encode($res);
	}

	/**
	 * custom function that returns all data related to the content table
	 * @return - record set data via query string
	 */
	private function json_content() {
		$query = "SELECT content.contentId, content.title, content.abstract, content.award FROM content ";
		$params = [];
		$where = " WHERE ";
		$doneWhere = FALSE;

		/**
		 * @param - Used to find content based on specific session Id
		 */
		if(isset($_REQUEST['sessionId'])) {
			$query .= " INNER JOIN sessions_content on 
			(content.contentId = sessions_content.contentId)
			INNER JOIN sessions on
			(sessions_content.sessionId = sessions.sessionId) 
			";
	   
			$where .= " sessions.sessionId = :sessionId ";
			$doneWhere = TRUE;
			$params["sessionId"] = $_REQUEST['sessionId'];
		}

		/**
		 * @param - Used to find content for specific authorId
		 */
		if(isset($_REQUEST['authorId'])) {
			$query .= " INNER JOIN content_authors on
			(content.contentId = content_authors.contentId)
			INNER JOIN authors on
			(content_authors.authorId = authors.authorId)
			";

			$where .= " authors.authorId = :authorId ";
			$doneWhere = TRUE;
			$params["authorId"] = $_REQUEST['authorId'];
		}

		/**
		 * @param - Find content with matching title
		 */
		if (isset($_REQUEST['title'])) {
			$query .= " WHERE title = :title";
			$params = ["title" => $_REQUEST['title']];
		}

		/**
		 * @param - Find content with similar abstract
		 */
		if (isset($_REQUEST['abstract'])) {
			$query .= " WHERE abstract LIKE :abstract";
			$params = ["abstract" => "%" . $_REQUEST['abstract'] . "%"];
		}

		/**
		 * @param - Find content with a specific award
		 */
		if (isset($_REQUEST['award'])) {
			$query .= " WHERE award = :award";
			$params = ["award" => $_REQUEST['award']];
		}

		/**
		 * @param - Limit content with entered integer
		 */
		if (isset($_REQUEST['limit'])) {
			$query .= " LIMIT :limit";
			$params = ["limit" => $_REQUEST['limit']];
		}

		/**
		 * @param - Limit content of 10 per page and specify page number with integer
		 */
		if (isset($_REQUEST['page'])) {
			$query .= " ORDER BY title";
			$query .= " LIMIT 10 ";
			$query .= "OFFSET ";
			$query .= 10 * ($this->sanitiseNum($_REQUEST['page']) - 1);
		}
		$query .= $doneWhere ? $where : "";
		$nextpage = null;
		$res = json_decode($this->recordset->getJSONRecordSet($query, $params),true);
		
		$res['status'] = 200;
		$res['message'] = "ok";
		$res['next_page'] = $nextpage;
		return json_encode($res);
	}
	

	/**
	 * custom function that returns all data related to the rooms table
	 * @return - record set data via query string
	 */
	private function json_rooms()
	{
		$query  = "SELECT roomId, name FROM rooms";
		$params = [];
		$where = " WHERE ";
		$doneWhere = FALSE;

		/**
		 * @param - Search functionality to find rooms with similar name
		 */
		if (isset($_REQUEST['search'])) {
			$where .= " name LIKE :initial";
			$doneWhere = TRUE;
			$params = ["initial" => "%" . $_REQUEST['search'] . "%"];
		}

		/**
		 * @param - Search for a room with specific id
		 */
		if (isset($_REQUEST['id'])) {
			$where .= " roomId = :id";
			$doneWhere = TRUE;
			$params = ["id" => $_REQUEST['id']];
		}

		/**
		 * @param - Limit rooms with entered integer
		 */
		if (isset($_REQUEST['limit'])) {
			$query .= " LIMIT :limit";
			$params = ["limit" => $_REQUEST['limit']];
		}

		/**
		 * @param - Order rooms 10 per page and search for page with entered integer
		 */
		if (isset($_REQUEST['page'])) {
			$query .= " ORDER BY name";
			$query .= " LIMIT 10 ";
			$query .= "OFFSET ";
			$query .= 10 * ($this->sanitiseNum($_REQUEST['page']) - 1);
		}
		$query .= $doneWhere ? $where : "";
	  
		$nextpage = null;

		$res = json_decode($this->recordset->getJSONRecordSet($query, $params),true);
		
		$res['status'] = 200;
		$res['message'] = "ok";
		$res['next_page'] = $nextpage;
		return json_encode($res);
	}

	/**
	 * custom function that returns all data related to the sessionTypes table
	 * @return - record set data via query string
	 */
	private function json_sessionTypes()
	{
		$query  = "SELECT name FROM session_types";
		$params = [];
		$where = " WHERE ";
		$doneWhere = FALSE;

		/**
		 * @param  - Search for sessionType with similar name
		 */
		if (isset($_REQUEST['search'])) {
			$where .= " name LIKE :initial";
			$doneWhere = TRUE;
			$params = ["initial" => "%" . $_REQUEST['search'] . "%"];
		}

		/**
		 * @param - Search for sessionType with matching id
		 */
		if (isset($_REQUEST['id'])) {
			$where .= " typeId = :id";
			$doneWhere = TRUE;
			$params = ["id" => $_REQUEST['id']];
		}

		/**
		 * @param - Limit sessionTypes with entered integer
		 */
		if (isset($_REQUEST['limit'])) {
			$query .= " LIMIT :limit";
			$params = ["limit" => $_REQUEST['limit']];
		}

		/**
		 * @param - Entered page number to view content
		 */
		if (isset($_REQUEST['page'])) {
			$query .= " ORDER BY name";
			$query .= " LIMIT 10 ";
			$query .= "OFFSET ";
			$query .= 10 * ($this->sanitiseNum($_REQUEST['page']) - 1);
		}
		$query .= $doneWhere ? $where : "";
		$nextpage = null;
		$res = json_decode($this->recordset->getJSONRecordSet($query, $params),true);
		
		$res['status'] = 200;
		$res['message'] = "ok";
		$res['next_page'] = $nextpage;
		return json_encode($res);
	}

	/**
	 * custom function that returns all data related to the sessions table
	 * @return - record set data via query string
	 */
	private function json_sessions() {
		$query  = "SELECT sessions.sessionId, sessions.name, sessions.chairId, slots.startHour, slots.startMinute, slots.endHour, slots.endMinute, slots.dayString, slots.type AS slot, session_types.name AS sessionType,
		rooms.name AS room FROM sessions INNER JOIN slots on (slots.slotId = sessions.slotId) INNER JOIN session_types on (session_types.typeId = sessions.typeId) INNER JOIN rooms on (sessions.roomId = rooms.roomId) ";
		$params = [];
		$where = " WHERE ";
		$doneWhere = FALSE;

		/**
		 * @param - Relevant sessions to matching Author
		 */
		if (isset($_REQUEST['authorId'])) {
			$query .= " 
			INNER JOIN sessions_content on (sessions.sessionId = sessions_content.sessionId)
			INNER JOIN content on (sessions_content.contentId = content.contentId)
			INNER JOIN content_authors on (content.contentId = content_authors.contentId)
			INNER JOIN authors on (content_authors.authorId = authors.authorId)
			";
			$where .= " authors.authorId = :authorId LIMIT";
			$doneWhere = TRUE;
			$params["authorId"] = $_REQUEST['authorId'];
		}
	  
		/**
		 * @param - Search
		 */
		if (isset($_REQUEST['search'])) {  
		  $where .= " name LIKE :search";
		  $doneWhere = TRUE;
		  $params["search"] = $_REQUEST['search'];
		} 

		/**
		 * @param - Find sessions with same id
		 */
		if (isset($_REQUEST['id'])) {	  
			$where .= " sessionId = :sessionId ";
			$doneWhere = TRUE;
			$params["sessionId"] = $_REQUEST['id'];
		}
	
		/**
		 * @param - Search for page with limited content.
		 */
		if (isset($_REQUEST['page'])) {
		  $query .= " ORDER BY film_id";
		  $query .= " LIMIT 10 ";
		  $query .= " OFFSET ";
		  $query .= 10 * ($this->sanitiseNum($_REQUEST['page'])-1);
		}
	  
		$query .= $doneWhere ? $where : "";
		$nextpage = null;
		$res = json_decode($this->recordset->getJSONRecordSet($query, $params),true);
		
		$res['status'] = 200;
		$res['message'] = "ok";
		$res['next_page'] = $nextpage;
		return json_encode($res);
	}

	/**
	 * custom function that returns all data related to the sessions table
	 * @return - record set data via query string
	 */
	private function json_sessionChair() {
		$query  = "SELECT authors.name AS chairName, authors.authorId FROM authors ";
		$params = [];
		$where = " WHERE ";
		$doneWhere = FALSE;

		/**
		 * @param - Find session Chair.
		 */
		if (isset($_REQUEST['chairId'])) {
			$query .= "
			INNER JOIN content_authors on (authors.authorId = content_authors.authorId)
			INNER JOIN content on (content_authors.contentId = content.contentId)
			INNER JOIN sessions_content on (sessions_content.contentId = content.contentId)
			INNER JOIN sessions on (sessions_content.sessionId = sessions.sessionId)
			";
			$where .= " sessions.chairId = :chair ";
			$doneWhere = TRUE;
			$params["chair"] = $_REQUEST['chairId'];
		}
	  
		$query .= $doneWhere ? $where : "";
		$nextpage = null;
		$res = json_decode($this->recordset->getJSONRecordSet($query, $params),true);
		
		$res['status'] = 200;
		$res['message'] = "ok";
		$res['next_page'] = $nextpage;
		return json_encode($res);
	}

	/**
	 * custom slots function that returns all data related to the slots table
	 * @return - record set data via query string.
	 */
	private function json_slots() {
		$query = "SELECT * FROM slots";
		$params = [];
		$where = " WHERE ";
		$doneWhere = FALSE;

		/**
		 * @param - Find slot with matching type
		 */
		if(isset($_REQUEST['type'])) {
			$where .= " type = :type";
			$doneWhere = TRUE;
			$params = ["type" => $_REQUEST['type']];
		}

		/**
		 * @param - Find slot with matching day
		 */
		if(isset($_REQUEST['day'])) {
			$where .= " dayString = :dayString";
			$doneWhere = TRUE;
			$params = ["dayString" => $_REQUEST['day']];
		}
		
		$query .= $doneWhere ? $where : "";
		$nextpage = null;
		$res = json_decode($this->recordset->getJSONRecordSet($query, $params),true);
		
		$res['status'] = 200;
		$res['message'] = "ok";
		$res['next_page'] = $nextpage;
		return json_encode($res);
	}

	/**
	 * Returns json page to display json data
	 * @return - page type.
	 */
	public function get_page()
	{
		return $this->page;
	}

	/**
	 * Login function that validates a user to gain authentication
	 * @param - input email via POST request
	 * @param - input password via POST request
	 * @return - status code, message and token.
	 */
	private function json_login() {
		$msg = "Invalid request. Username and password required!";
		$status = 400;
		$token = null;
		$input = json_decode(file_get_contents("php://input"));
		$jwtkey = JWTKEY;

		if ($input) {
			if(isset($input->email) && isset($input->password)) {
				$query = "SELECT username, admin, password FROM users WHERE email LIKE :email";
				$params = ["email" => $input->email];
				$res = json_decode($this->recordset->getJSONRecordSet($query, $params), true);

				if (password_verify($input->password, $res['data'][0]['password'])) {
					$msg = "Admin authorised. Welcome ". $res['data'][0]['username'];
					$status = 200;
					$token = array();
					$token['email'] = $input->email;
					$token['username'] = $res['data'][0]['username'];
					$token['admin'] = $res['data'][0]['admin'];
					$adminStatus = $res['data'][0]['admin'];
					$token['iat'] = time();
					$token['exp'] = time() + 60 * 60;
					$token = \Firebase\JWT\JWT::encode($token, $jwtkey);
				} else { 
					$msg = "username or password are invalid";
					$status = 401;
				}
			}
		}
		return json_encode(array("status" => $status, "message" => $msg, "token" => $token, "adminStatus" => $adminStatus));
	}

	/**
	 * Updates the required attributes regarding a valid token being present
	 * @param - input token via POST request
	 * @return - status code, message and token
	 */
	private function json_update() {
		$msg = "Invalid request. Details are required!";
		$status = 400;
		$input = json_decode(file_get_contents("php://input"));
		$jwtkey = JWTKEY;


		if(!$input) {
			return json_encode(array("status" => 400, "message" => "Invalid Request"));
		}
		if(!isset($input->token)) {
			return json_encode(array("status" => 401, "message" => "Not Authorised"));
		}
		if(!isset($input->title) || !isset($input->sessionId)) {
			return json_encode(array("status" => 400, "message" => "Invalid Request", "title" => $input->title, "session" => $input->sessionId));
		}

		try {
			$tokenDecoded = \Firebase\JWT\JWT::decode($input->token, $jwtkey, array('HS256'));
		} catch (UnexpectedValueException $e) {
			return json_encode(array("status" => 401, "message" => $e->getMessage()));
		}

		$query = "UPDATE sessions SET name = :title WHERE sessionId = :sessionId;";
		$params = ["sessionId" => $input->sessionId, "title" => $input->title];
		$res = json_decode($this->recordset->getJSONRecordSet($query, $params),true);

		$msg = "Update successful.";
		$status = 200;
		return json_encode(array("status" => $status, "message" => $msg));
	}

	/**
	 * Default function to return 404 status when invalid api entered in the url
	 * @return - message and header status code
	 */
	private function defaultMessage() {
		http_response_code(404);
		return json_encode(["message" => "Invalid API endpoint entered!"]);
	}
}
?>
