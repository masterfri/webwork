<?php

class Application extends CActiveRecord  
{
	const STATUS_HAS_WEB = 0x01;
	const STATUS_HAS_GIT = 0x02;
	const STATUS_HAS_DB = 0x04;
	
	public $create_repo = 0;
	protected $_lastBuild;
	protected static $_cf;
	
	protected static $allowedVhostDirectives = array(
		'AddDefaultCharset',
		'DefaultType',
		'ErrorDocument',
		'LogLevel',
		'ServerAdmin',
		'ServerAlias',
	);
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{application}}';
	}
	
	public function attributeLabels()
	{
		return array(
			'db_name' => Yii::t('application', 'Database Name'),
			'db_password' => Yii::t('application', 'Password'),
			'db_user' => Yii::t('application', 'User'),
			'description' => Yii::t('application', 'Description'),
			'document_root' => Yii::t('application', 'Document Root'),
			'log_directory' => Yii::t('application', 'Logs Directory'),
			'vhost_options' => Yii::t('application', 'Vhost Directives'),
			'git' => Yii::t('application', 'Git Repository URL'),
			'create_repo' => Yii::t('application', 'Create Repository'),
			'git_branch' => Yii::t('application', 'Branch'),
			'name' => Yii::t('application', 'Name'),
			'project' => Yii::t('application', 'Project'),
			'project_id' => Yii::t('application', 'Project'),
			'domain' => Yii::t('application', 'Domain'),
			'entitiesToBuild' => Yii::t('application', 'Entities to Build'),
			'schemesToBuild' => Yii::t('application', 'Schemes to Build'),
			'packages' => Yii::t('application', 'Packages to Include'),
			'buildOptionsFlat' => Yii::t('application', 'Options'),
		);
	}
	
	public function rules()
	{
		return array(
			array('	name', 
					'required', 'on' => 'create'),
			array('	name', 
					'unique', 'on' => 'create'),
			array('	name', 
					'match', 'pattern' => '/^[a-z0-9_]+([.-][a-z0-9_]+)*$/i', 'on' => 'create'),
			array('	name',
					'length', 'max' => 30, 'on' => 'create'),
			array('	description', 
					'length', 'max' => 16000, 'on' => 'create, update'),
			array('	document_root',
					'required', 'on' => 'configwebserver'),
			array('	document_root,
					log_directory',
					'length', 'max' => 100, 'on' => 'configwebserver'),
			array('	vhost_options',
					'length', 'max' => 1600, 'on' => 'configwebserver'),
			array('	vhost_options',
					'validateVhostDirectives', 'on' => 'configwebserver'),
			array('	git',
					'RequiredIf', 'condition' => '$model->create_repo == 0', 'jscondition' => "js:!$('#Application_create_repo').get(0).checked", 'on' => 'configgit'),
			array('	git',
					'length', 'max' => 250, 'on' => 'configgit'),
			array('	git_branch',
					'length', 'max' => 100, 'on' => 'configgit'),
			array(' create_repo',
					'boolean', 'on' => 'configgit'),
			array('	db_name,
					db_password,
					db_user',
					'required', 'on' => 'configdb'),
			array('	db_name,
					db_password,
					db_user',
					'match', 'pattern' => '/^[a-z0-9_]+$/i', 'on' => 'configdb'),
			array('	db_name,
					db_password,
					db_user',
					'length', 'max' => 100, 'on' => 'configdb'),
			array('	db_name',
					'unique', 'criteria' => array('condition' => 'id != :id', 'params' => array(':id' => $this->id)), 'on' => 'configdb'),
			array('	db_user',
					'unique', 'criteria' => array('condition' => 'id != :id', 'params' => array(':id' => $this->id)), 'on' => 'configdb'),
			array(' entitiesToBuild,
					schemesToBuild',
					'required', 'on' => 'build'),
			array(' packages,
					buildOptionsFlat',
					'safe', 'on' => 'build'),
		);
	}
	
	public function relations()
	{
		return array(
			'created_by' => array(self::BELONGS_TO, 'User', 'created_by_id'),
			'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
			'entities' => array(self::HAS_MANY, 'AppEntity', 'application_id'),
		);
	}

	public function behaviors()
	{
		return array(
			array(
				'class' => 'StampBehavior',
				'create_time_attribute' => 'time_created',
				'created_by_attribute' => 'created_by',
			),
			array(
				'class' => 'RelationBehavior',
				'attributes' => array(
					'created_by',
					'project',
					'entities' => array(
						'cascadeDelete' => true,
					),
				),
			),
		);
	}
	
	protected function afterDelete()
	{
		Yii::app()->cf->importLib('FileHelper');
		$appdir = $this->getCFWorkdir(false);
		if (is_dir($appdir)) {
			Codeforge\FileHelper::rm($appdir);
		}
		$appdir = $this->getGitWorkdir(false);
		if (is_dir($appdir)) {
			Codeforge\FileHelper::rm($appdir);
		}
		parent::afterDelete();
	}
	
	public function search($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->alias = 'application';
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'application.name',
			),
		));
	}
	
	public function __toString()
	{
		return $this->name;
	}
	
	public static function getAllowedVhostDirectives()
	{
		return self::$allowedVhostDirectives;
	}
	
	public function validateVhostDirectives($attribute, $params)
	{
		$allow = self::getAllowedVhostDirectives();
		foreach (explode("\n", $this->$attribute) as $line) {
			$dir = trim($line);
			if ('' !== $dir) {
				if (!preg_match('/^([a-z]+)\b/i', $dir, $m) || !in_array($m[1], $allow)) {
					$this->addError($attribute, Yii::t('application', 'Illegal vhost directive'));
					break;
				}
			}
		}
	}
	
	public function getDomain()
	{
		return sprintf('%s.%s', $this->name, GeneralOptions::instance()->app_domain);
	}
	
	public function getGitRepoName()
	{
		return preg_replace('/[^0-9a-z_-]/i', '_', $this->name);
	}
	
	public function getIsGitLocal()
	{
		return ($this->status & self::STATUS_HAS_GIT) && substr($this->git, 0, 1) == '/';
	}
	
	public function setStatus($status)
	{
		$this->status = $this->status | $status;
		$this->update(array('status'));
	}
	
	public function unsetStatus($status)
	{
		$this->status = $this->status & ~$status;
		$this->update(array('status'));
	}
	
	protected function createWebShCommand($class)
	{
		$host = GeneralOptions::instance()->httpsh_host;
		$port = GeneralOptions::instance()->httpsh_port;
		$login = GeneralOptions::instance()->httpsh_login;
		$passw = GeneralOptions::instance()->httpsh_password;
		return new $class($login, $passw, $host, $port);
	}
	
	public function setupVhost()
	{
		$command = $this->createWebShCommand('ApacheHttpShCommand');
		return $command->setupVhost(array(
			'domain' => $this->getDomain(),
			'document_root' => $this->document_root,
			'log_directory' => $this->log_directory,
			'vhost_options' => $this->vhost_options,
		));
	}
	
	public function setupGit()
	{
		$command = $this->createWebShCommand('GitHttpShCommand');
		$response = $command->setupGit(array(
			'domain' => $this->getDomain(),
			'name' => $this->getGitRepoName(),
			'url' => $this->git,
			'create' => $this->create_repo,
		));
		if ($this->create_repo == 1 && $response->getIsSuccess()) {
			$data = $response->getData();
			$this->git = $data['url'];
			$this->update(array('git'));
		}
		return $response;
	}
	
	public function setupDb()
	{
		$command = $this->createWebShCommand('DbHttpShCommand');
		return $command->setupDb(array(
			'db_name' => $this->db_name,
			'user_name' => $this->db_user,
			'password' => $this->db_password,
		));
	}
	
	public function makePull()
	{
		$command = $this->createWebShCommand('GitHttpShCommand');
		return $command->pull(array(
			'domain' => $this->getDomain(),
			'url' => $this->git,
			'branch' => $this->git_branch,
		));
	}
	
	public function cleanup($options)
	{
		$command = $this->createWebShCommand('CleanupHttpShCommand');
		return $command->cleanup(array(
			'domain' => $this->getDomain(),
			'db_name' => $this->db_name,
			'db_user' => $this->db_user,
			'repo_name' => $this->getGitRepoName(),
			'workdir' => $this->getGitWorkdir(),
			'options' => $options,
		));
	}
	
	public function pullWorkCopy($branch)
	{
		$command = $this->createWebShCommand('GitHttpShCommand');
		return $command->pullWorkCopy(array(
			'workpath' => $this->getGitWorkdir(),
			'url' => $this->git,
			'branch' => $branch,
		));
	}
	
	public function pushWorkCopy($branch, $message)
	{
		$command = $this->createWebShCommand('GitHttpShCommand');
		return $command->pushWorkCopy(array(
			'workpath' => $this->getGitWorkdir(),
			'url' => $this->git,
			'branch' => $branch,
			'message' => $message,
		));
	}
	
	public function releaseWorkCopy($overwrite=false)
	{
		$command = $this->createWebShCommand('CFHttpShCommand');
		$response = $command->releaseWorkCopy(array(
			'gitpath' => $this->getGitWorkdir(),
			'cfpath' => $this->getCFWorkdir(),
			'overwrite' => $overwrite,
		));
		if ($response->getIsSuccess()) {
			if ($response->hasData('checksum')) {
				$checksum = array();
				foreach ($response->getData('checksum') as $line) {
					list($sum, $file) = explode(' ', $line);
					$checksum[trim($file)] = trim($sum);
				}
				$this->getCF()->updateChecksum($checksum);
			}
		}
		return $response;
	}
	
	public function getCF()
	{
		if (null === self::$_cf) {
			self::$_cf = Yii::app()->cf;
			self::$_cf->setup($this->getCFWorkdir());
		}
		return self::$_cf;
	}
	
	public function getCFWorkdir($create=true)
	{
		if ($create) {
			$dir = Yii::app()->getRuntimePath() . '/cf-build';
			if (!is_dir($dir)) {
				if (!@mkdir($dir)) {
					throw new CException("Can't create directory $dir");
				}
			} elseif (!is_writable($dir)) {
				throw new CException("Directory $dir is not writable");
			}
			$dir .= '/app-' . $this->id;
			if (!is_dir($dir)) {
				if (!@mkdir($dir)) {
					throw new CException("Can't create directory $dir");
				}
			} elseif (!is_writable($dir)) {
				throw new CException("Directory $dir is not writable");
			}
		} else {
			$dir = Yii::app()->getRuntimePath() . '/cf-build/app-' . $this->id;
		}
		return $dir;
	}
	
	public function getGitWorkdir($create=true)
	{
		if ($create) {
			$dir = Yii::app()->getRuntimePath() . '/git-work-dir';
			if (!is_dir($dir)) {
				if (!@mkdir($dir)) {
					throw new CException("Can't create directory $dir");
				}
			} elseif (!is_writable($dir)) {
				throw new CException("Directory $dir is not writable");
			}
			$dir .= '/app-' . $this->id;
			if (!is_dir($dir)) {
				if (!@mkdir($dir)) {
					throw new CException("Can't create directory $dir");
				}
				if (!@chmod($dir, 0775)) {
					throw new CException("Can't set permissions for directory $dir");
				}
			} elseif ((fileperms($dir) & 0775) != 0775) {
				throw new CException("Directory $dir is not writable for owner group");
			}
		} else {
			$dir = Yii::app()->getRuntimePath() . '/git-work-dir/app-' . $this->id;
		}
		return $dir;
	}
	
	protected function getLastBuild()
	{
		if (null === $this->_lastBuild) {
			if (!empty($this->last_build_json)) {
				$this->_lastBuild = CJSON::decode($this->last_build_json);
			} else {
				$this->_lastBuild = array(
					'entities' => array(),
					'schemes' => array(),
					'packages' => array(),
					'options' => array(),
				);
			}
		}
		return $this->_lastBuild;
	}
	
	public function getEntitiesToBuild()
	{
		$last_build = $this->getLastBuild();
		return isset($last_build['entities']) ? $last_build['entities'] : array();
	}
	
	public function getSchemesToBuild()
	{
		$last_build = $this->getLastBuild();
		return isset($last_build['schemes']) ? $last_build['schemes'] : array();
	}
	
	public function getPackages()
	{
		$last_build = $this->getLastBuild();
		return isset($last_build['packages']) ? $last_build['packages'] : array();
	}
	
	public function getBuildOptions()
	{
		$last_build = $this->getLastBuild();
		return isset($last_build['options']) ? $last_build['options'] : array();
	}
	
	public function setEntitiesToBuild($val)
	{
		$this->getLastBuild();
		$this->_lastBuild['entities'] = $val;
		$this->last_build_json = CJSON::encode($this->_lastBuild);
	}
	
	public function setSchemesToBuild($val)
	{
		$this->getLastBuild();
		$this->_lastBuild['schemes'] = $val;
		$this->last_build_json = CJSON::encode($this->_lastBuild);
	}
	
	public function setPackages($val)
	{
		$this->getLastBuild();
		$this->_lastBuild['packages'] = $val;
		$this->last_build_json = CJSON::encode($this->_lastBuild);
	}
	
	public function setBuildOptions($val)
	{
		$this->getLastBuild();
		$this->_lastBuild['options'] = $val;
		$this->last_build_json = CJSON::encode($this->_lastBuild);
	}
	
	public function getBuildOptionsFlat()
	{
		$result = array();
		foreach ($this->getBuildOptions() as $key => $val) {
			$result[] = $key . ' ' . $val;
		}
		return implode("\n", $result);
	}
	
	public function setBuildOptionsFlat($val)
	{
		$data = array();
		$lines = explode("\n", $val);
		foreach ($lines as $line) {
			$line = trim($line);
			if ('' != $line) {
				$couple = preg_split('/\s+/', $line, 2);
				$key = trim(array_shift($couple));
				$val = trim(array_shift($couple));
				$data[$key] = $val;
			}
		}
		$this->setBuildOptions($data);
	}
}
