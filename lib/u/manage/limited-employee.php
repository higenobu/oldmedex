<?php // -*- mode: php; coding: euc-japan -*-
include_once $_SERVER['DOCUMENT_ROOT'].'/lib/u/manage/employee.php';

class list_of_limited_employees extends list_of_employees {
	var $allow_not_so_super_but_still_is = 0;

	function list_of_limited_employees($prefix, $cfg=NULL) {
		$d = mx_prepare_userinfo(NULL);
		$am_superuser = ($d["����ObjectID"] == 1);

		list_of_employees::list_of_employees($prefix, $cfg);

		$this->am_superuser = $am_superuser;
		if (!$am_superuser) {
			$extra = (($this->allow_not_so_super_but_still_is)
				  ? ' AND NOT (E."����" = 1 AND E."����" = 1)'
				  : ' AND NOT (E."����" = 1)');
			$this->so_config['STMT'] .= $extra;
		}
	}

}

class limited_employee_edit extends employee_edit {

	function limited_employee_edit($prefix) {
		employee_edit::employee_edit($prefix);
		$d = mx_prepare_userinfo(NULL);
		$this->am_superuser = ($d["����ObjectID"] == 1);
	}

	function find_enum_values($col) {
		if ($col != '����̾') {
			return array('�Ф�' => '�Ф�');
		}
		$db = mx_db_connect();
		$stmt = <<<SQL
			SELECT "ObjectID", "����"
			FROM "�������ɽ"
			WHERE "Superseded" IS NULL
SQL;
		$ret = array();
		$super = NULL;
		foreach (mx_db_fetch_all($db, $stmt) as $c) {
			if ($c['ObjectID'] == 1)
				$super = $c['����'];
			else
				$ret[$c['ObjectID']] = $c['����'];
		}
		if ($this->am_superuser && $super)
			$ret[1] = $super;
		return $ret;
	}

	function _validate() {
		$errs = 0;
		if (!$this->am_superuser) {
			$dont_create_superuser = 0;

			if ($this->id != '') {
				# Do not allow changing existing superuser
				$d = $this->fetch_data($this->id);
				if ($d['����'] == 1)
					$dont_create_superuser = 1;
			}

			# Only allow ordinary users
			$d = $this->data;
			if ($d['����'] == 1)
				$dont_create_superuser = 1;

			if ($dont_create_superuser) {
				$this->err('�����ѡ��桼����'.
					   '���긢�¤�����ޤ���'.
					   "�Խ�����ߤ��Ʋ�����\n");
				$errs++;
			}
		}
		$ok = employee_edit::_validate();
		if ($ok == 'ok' && $errs == 0)
			return 'ok';
		return NULL;
	}

}

class list_of_limited_employees_for_password extends list_of_limited_employees {
	var $allow_not_so_super_but_still_is = 1;
}

?>
