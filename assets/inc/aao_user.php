<?php
if(count(get_included_files()) ==1) die(); //Direct Access Not Permitted

class aaoUSer extends User
{
    public function loginEmail($email = null, $aaoUser = null)
    {
        $user = $this->find($email, 1);
            
            //sjekk om brukeren er registert i US
        if(!$user){
            $fields = [
                'username' => $aaoUser->mail,
                'fname' => $aaoUser->givenName,
                'lname' => $aaoUser->surname,
                'email' => $aaoUser->mail,
                //'password' => password_hash(Input::get('password', true), PASSWORD_BCRYPT, ['cost' => 13]),
                'permissions' => 1,
                'join_date' => $join_date,
                'email_verified' => $pre,
                'vericode' => $vericode,
                'vericode_expiry' => $vericode_expiry,
                'oauth_tos_accepted' => true,
                'active'=>1,
                'aao_id' => $aaoUser->id
            ];
            
            $theNewId = $user->create($fields);
        }
        if($user->aao_id == NULL) $this->_db->query('UPDATE users SET  	aao_id = ?,  aao_id  = logins + 1 WHERE id = ?', [$date, $this->data()->id]);
        
        Session::put($this->_sessionName, $this->data()->id);

        $date = date('Y-m-d H:i:s');
        $this->_db->query('UPDATE users SET last_login = ?, logins = logins + 1 WHERE id = ?', [$date, $this->data()->id]);
        $_SESSION['last_confirm'] = date('Y-m-d H:i:s');
        $ip = ipCheck();
        $this->_db->insert('logs', ['logdate' => $date, 'user_id' => $this->data()->id, 'logtype' => 'login', 'lognote' => 'User logged in.', 'ip' => $ip]);
        
        $q = $this->_db->query('SELECT id FROM us_ip_list WHERE ip = ?', [$ip]);
        $c = $q->count();
        if ($c < 1) {
            $this->_db->insert('us_ip_list', [
                'user_id' => $this->data()->id,
                'ip' => $ip,
                'timestamp' => date('Y-m-d H:i:s'),
            ]);
        } else {
            $f = $q->first();
            $this->_db->update('us_ip_list', $f->id, [
                'user_id' => $this->data()->id,
                'ip' => $ip,
                'timestamp' => date('Y-m-d H:i:s'),
            ]);
        }
        
        return true;
    }
}
?>