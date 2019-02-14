<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as cron management
 * @package   CodeIgniter
 * @category  Controller
 * @author    Developer
 */
class Cron extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function send_notifications() {
        ini_set('max_execution_time', 600); // 10 minutes
        /* Get Pending Notifications */
        $pending_notifications = $this->Common_model->getAllwhere(ADMIN_NOTIFICATION, array('status' => 'PENDING'), 'id', 'ASC');
        if (!empty($pending_notifications['result'])) {
            foreach ($pending_notifications['result'] as $pn) {

                $device_in = array('OFFER_DEVICE', 'NEWS_DEVICE', 'ADMIN_DEVICE');
                if (in_array($pn->notification_type, $device_in) && !empty($pn->device_ids)) {
                    $device_ids = (!empty($pn->device_ids)) ? unserialize($pn->device_ids) : array();
                    if (!empty($device_ids)) {
                        $device_ids = array_values($device_ids);
                        $final_devices = array_slice($device_ids, 0, 50);
                        if (!empty($final_devices)) {
                            foreach ($final_devices as $key => $fu) {
                                $device_details = $this->Common_model->getsingle(USERS_DEVICE_HISTORY, array('id' => $fu));
                                if (!empty($device_details)) {
                                    if ($device_details->device_type == 'ANDROID' && !empty($device_details->device_key)) {
                                        $device_badges = $device_details->device_badges + 1;
                                        $notification_type = "";
                                        if ($pn->notification_type == "OFFER_DEVICE") {
                                            $notification_type = "OFFER";
                                        } else if ($pn->notification_type == "NEWS_DEVICE") {
                                            $notification_type = "NEWS";
                                        }else if($pn->notification_type == "ADMIN_DEVICE"){
                                            $notification_type = "ADMIN";
                                        }
                                        $data_array = array(
                                            'title' => $pn->title,
                                            'body' => $pn->message,
                                            'type' => $notification_type,
                                            'type_id' => $pn->type_id,
                                            'user_id' => 0,
                                            'badges' => $device_badges,
                                        );
                                        $status = send_android_notification($data_array, $device_details->device_key, $device_badges);
                                        if ($status) {
                                            if (($user_key = array_search($fu, $device_ids)) !== false) {
                                                unset($device_ids[$user_key]);
                                            }
                                            /* Update user notification sent status */
                                            $this->Common_model->updateFields(USER_NOTIFICATION, array('is_send' => '1'), array('notification_parent_id' => $pn->id, 'device_reciever_id' => $fu));
                                            if (!empty($fu)) {
                                                $this->Common_model->updateFields(USERS_DEVICE_HISTORY, array('device_badges' => $device_badges), array('id' => $fu));
                                            }
                                        }
                                    }

                                    if ($device_details->device_type == 'IOS' && !empty($device_details->device_key)) {
                                        $device_badges = $device_details->device_badges + 1;
                                        $notification_type = "";
                                        if ($pn->notification_type == "OFFER_DEVICE") {
                                            $notification_type = "OFFER";
                                        } else if ($pn->notification_type == "NEWS_DEVICE") {
                                            $notification_type = "NEWS";
                                        }else if($pn->notification_type == "ADMIN_DEVICE"){
                                            $notification_type = "ADMIN";
                                        }
                                        $params = array(
                                            'title' => $pn->title,
                                            'type' => $notification_type,
                                            'type_id' => $pn->type_id,
                                            'user_id' => 0,
                                        );
                                        $status = send_ios_notification($device_details->device_key, $pn->message, $params, $device_badges);
                                        if ($status) {
                                            if (($user_key = array_search($fu, $device_ids)) !== false) {
                                                unset($device_ids[$user_key]);
                                            }
                                            /* Update user notification sent status */
                                            $this->Common_model->updateFields(USER_NOTIFICATION, array('is_send' => '1'), array('notification_parent_id' => $pn->id, 'device_reciever_id' => $fu));
                                            if (!empty($fu)) {
                                                $this->Common_model->updateFields(USERS_DEVICE_HISTORY, array('device_badges' => $device_badges), array('id' => $fu));
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            $this->Common_model->updateFields(ADMIN_NOTIFICATION, array('status' => 'COMPLETED'), array('id' => $pn->id));
                        }

                        /* Update User ids from admin notifications */
                        $updated_device_ids = serialize(array_values($device_ids));
                        $this->Common_model->updateFields(ADMIN_NOTIFICATION, array('device_ids' => $updated_device_ids), array('id' => $pn->id));
                    } else {
                        /* Update admin notification status */
                        $this->Common_model->updateFields(ADMIN_NOTIFICATION, array('status' => 'COMPLETED'), array('id' => $pn->id));
                    }
                } else {
                    $flag = FALSE;
                    /* Get user ids */
                    $user_ids = (!empty($pn->user_ids)) ? unserialize($pn->user_ids) : array();
                    if (!empty($user_ids)) {
                        $user_ids = array_values($user_ids);
                        /* Get first 50 users */
                        $final_users = array_slice($user_ids, 0, 50);
                        if (!empty($final_users)) {
                            foreach ($final_users as $key => $fu) {
                                /* Get user info */
                                $user_details = $this->Common_model->getsingle(USERS, array('id' => $fu));
                                if (!empty($user_details)) {
                                   // if ($user_details->is_login == 1) {
                                        $flag = TRUE;
                                        if ($user_details->device_type == 'ANDROID' && !empty($user_details->device_id)) {
                                            $user_badges = $user_details->badges + 1;
                                            $data_array = array(
                                                'title' => $pn->title,
                                                'body' => $pn->message,
                                                'type' => $pn->notification_type,
                                                'type_id' => $pn->type_id,
                                                'user_id' => $fu,
                                                'badges' => $user_badges,
                                            );
                                            $status = send_android_notification($data_array, $user_details->device_id, $user_badges, $fu);
                                            if ($status) {
                                                if (($user_key = array_search($fu, $user_ids)) !== false) {
                                                    unset($user_ids[$user_key]);
                                                }
                                                /* Update user notification sent status */
                                                $this->Common_model->updateFields(USER_NOTIFICATION, array('is_send' => '1'), array('notification_parent_id' => $pn->id, 'reciever_id' => $fu));
                                            }
                                        }
                                        if ($user_details->device_type == 'IOS' && !empty($user_details->device_id)) {
                                            $user_badges = $user_details->badges + 1;
                                            $params = array(
                                                'title' => $pn->title,
                                                'type' => $pn->notification_type,
                                                'type_id' => $pn->type_id,
                                                'user_id' => $fu
                                            );
                                            $status = send_ios_notification($user_details->device_id, $pn->message, $params, $user_badges, $fu);
                                            if ($status) {
                                                if (($user_key = array_search($fu, $user_ids)) !== false) {
                                                    unset($user_ids[$user_key]);
                                                }
                                                /* Update user notification sent status */
                                                $this->Common_model->updateFields(USER_NOTIFICATION, array('is_send' => '1'), array('notification_parent_id' => $pn->id, 'reciever_id' => $fu));
                                            }
                                        }
                                   // } else {
                                   //     $flag = FALSE;
                                  //  }
                                }
                            }
                        } else {
                            //if ($flag) {
                                /* Update admin notification status */
                                $this->Common_model->updateFields(ADMIN_NOTIFICATION, array('status' => 'COMPLETED'), array('id' => $pn->id));
                            //}
                        }
                        //if ($flag) {
                            /* Update User ids from admin notifications */
                            $updated_user_ids = serialize(array_values($user_ids));
                            $this->Common_model->updateFields(ADMIN_NOTIFICATION, array('user_ids' => $updated_user_ids), array('id' => $pn->id));
                       // }
                    } else {
                      //  if ($flag) {
                            /* Update admin notification status */
                            $this->Common_model->updateFields(ADMIN_NOTIFICATION, array('status' => 'COMPLETED'), array('id' => $pn->id));
                       // }
                    }
                }
            }
        }
    }
    
    
    public function send_notifications_old() {
        ini_set('max_execution_time', 600); // 10 minutes
        /* Get Pending Notifications */
        $pending_notifications = $this->Common_model->getAllwhere(ADMIN_NOTIFICATION, array('status' => 'PENDING'), 'id', 'ASC');
        if (!empty($pending_notifications['result'])) {
            foreach ($pending_notifications['result'] as $pn) {

                $device_in = array('OFFER_DEVICE', 'NEWS_DEVICE');
                if (in_array($pn->notification_type, $device_in) && !empty($pn->device_ids)) {
                    $device_ids = (!empty($pn->device_ids)) ? unserialize($pn->device_ids) : array();
                    if (!empty($device_ids)) {
                        $device_ids = array_values($device_ids);
                        $final_devices = array_slice($device_ids, 0, 50);
                        if (!empty($final_devices)) {
                            foreach ($final_devices as $key => $fu) {
                                $device_details = $this->Common_model->getsingle(USERS_DEVICE_HISTORY, array('id' => $fu));
                                if (!empty($device_details)) {
                                    if ($device_details->device_type == 'ANDROID' && !empty($device_details->device_key)) {
                                        $device_badges = $device_details->device_badges + 1;
                                        $notification_type = "";
                                        if ($pn->notification_type == "OFFER_DEVICE") {
                                            $notification_type = "OFFER";
                                        } else if ($pn->notification_type == "NEWS_DEVICE") {
                                            $notification_type = "NEWS";
                                        }
                                        $data_array = array(
                                            'title' => $pn->title,
                                            'body' => $pn->message,
                                            'type' => $notification_type,
                                            'type_id' => $pn->type_id,
                                            'user_id' => 0,
                                            'badges' => $device_badges,
                                        );
                                        $status = send_android_notification($data_array, $device_details->device_key, $device_badges);
                                        if ($status) {
                                            if (($user_key = array_search($fu, $device_ids)) !== false) {
                                                unset($device_ids[$user_key]);
                                            }
                                            /* Update user notification sent status */
                                            $this->Common_model->updateFields(USER_NOTIFICATION, array('is_send' => '1'), array('notification_parent_id' => $pn->id, 'device_reciever_id' => $fu));
                                            if (!empty($fu)) {
                                                $this->Common_model->updateFields(USERS_DEVICE_HISTORY, array('device_badges' => $device_badges), array('id' => $fu));
                                            }
                                        }
                                    }

                                    if ($device_details->device_type == 'IOS' && !empty($device_details->device_key)) {
                                        $device_badges = $device_details->device_badges + 1;
                                        $notification_type = "";
                                        if ($pn->notification_type == "OFFER_DEVICE") {
                                            $notification_type = "OFFER";
                                        } else if ($pn->notification_type == "NEWS_DEVICE") {
                                            $notification_type = "NEWS";
                                        }
                                        $params = array(
                                            'title' => $pn->title,
                                            'type' => $notification_type,
                                            'type_id' => $pn->type_id,
                                            'user_id' => 0,
                                        );
                                        $status = send_ios_notification($device_details->device_key, $pn->message, $params, $device_badges);
                                        if ($status) {
                                            if (($user_key = array_search($fu, $device_ids)) !== false) {
                                                unset($device_ids[$user_key]);
                                            }
                                            /* Update user notification sent status */
                                            $this->Common_model->updateFields(USER_NOTIFICATION, array('is_send' => '1'), array('notification_parent_id' => $pn->id, 'device_reciever_id' => $fu));
                                            if (!empty($fu)) {
                                                $this->Common_model->updateFields(USERS_DEVICE_HISTORY, array('device_badges' => $device_badges), array('id' => $fu));
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            $this->Common_model->updateFields(ADMIN_NOTIFICATION, array('status' => 'COMPLETED'), array('id' => $pn->id));
                        }

                        /* Update User ids from admin notifications */
                        $updated_device_ids = serialize(array_values($device_ids));
                        $this->Common_model->updateFields(ADMIN_NOTIFICATION, array('device_ids' => $updated_device_ids), array('id' => $pn->id));
                    } else {
                        /* Update admin notification status */
                        $this->Common_model->updateFields(ADMIN_NOTIFICATION, array('status' => 'COMPLETED'), array('id' => $pn->id));
                    }
                } else {
                    $flag = FALSE;
                    /* Get user ids */
                    $user_ids = (!empty($pn->user_ids)) ? unserialize($pn->user_ids) : array();
                    if (!empty($user_ids)) {
                        $user_ids = array_values($user_ids);
                        /* Get first 50 users */
                        $final_users = array_slice($user_ids, 0, 50);
                        if (!empty($final_users)) {
                            foreach ($final_users as $key => $fu) {
                                /* Get user info */
                                $user_details = $this->Common_model->getsingle(USERS, array('id' => $fu));
                                if (!empty($user_details)) {
                                   // if ($user_details->is_login == 1) {
                                        $flag = TRUE;
                                        if ($user_details->device_type == 'ANDROID' && !empty($user_details->device_id)) {
                                            $user_badges = $user_details->badges + 1;
                                            $data_array = array(
                                                'title' => $pn->title,
                                                'body' => $pn->message,
                                                'type' => $pn->notification_type,
                                                'type_id' => $pn->type_id,
                                                'user_id' => $fu,
                                                'badges' => $user_badges,
                                            );
                                            $status = send_android_notification($data_array, $user_details->device_id, $user_badges, $fu);
                                            if ($status) {
                                                if (($user_key = array_search($fu, $user_ids)) !== false) {
                                                    unset($user_ids[$user_key]);
                                                }
                                                /* Update user notification sent status */
                                                $this->Common_model->updateFields(USER_NOTIFICATION, array('is_send' => '1'), array('notification_parent_id' => $pn->id, 'reciever_id' => $fu));
                                            }
                                        }
                                        if ($user_details->device_type == 'IOS' && !empty($user_details->device_id)) {
                                            $user_badges = $user_details->badges + 1;
                                            $params = array(
                                                'title' => $pn->title,
                                                'type' => $pn->notification_type,
                                                'type_id' => $pn->type_id,
                                                'user_id' => $fu
                                            );
                                            $status = send_ios_notification($user_details->device_id, $pn->message, $params, $user_badges, $fu);
                                            if ($status) {
                                                if (($user_key = array_search($fu, $user_ids)) !== false) {
                                                    unset($user_ids[$user_key]);
                                                }
                                                /* Update user notification sent status */
                                                $this->Common_model->updateFields(USER_NOTIFICATION, array('is_send' => '1'), array('notification_parent_id' => $pn->id, 'reciever_id' => $fu));
                                            }
                                        }
                                   // } else {
                                   //     $flag = FALSE;
                                  //  }
                                }
                            }
                        } else {
                            //if ($flag) {
                                /* Update admin notification status */
                                $this->Common_model->updateFields(ADMIN_NOTIFICATION, array('status' => 'COMPLETED'), array('id' => $pn->id));
                            //}
                        }
                        //if ($flag) {
                            /* Update User ids from admin notifications */
                            $updated_user_ids = serialize(array_values($user_ids));
                            $this->Common_model->updateFields(ADMIN_NOTIFICATION, array('user_ids' => $updated_user_ids), array('id' => $pn->id));
                       // }
                    } else {
                      //  if ($flag) {
                            /* Update admin notification status */
                            $this->Common_model->updateFields(ADMIN_NOTIFICATION, array('status' => 'COMPLETED'), array('id' => $pn->id));
                       // }
                    }
                }
            }
        }
    }

    public function test() {
        $device = "068DAC459D126CDCD6E47F97EB33769C87DDA5D70133589D9F2692BEC952A9DB";
        $params = array(
            'title' => "Booking Status",
            'type' => "Booking",
            'type_id' => 2
        );
        $status = send_ios_notification($device, 'hello bkkoing', $params, 1, 77);
        var_dump($status);
    }

    public function send_email() {
        ini_set('max_execution_time', 600); // 10 minutes
        /* Get Pending Notifications */
        $option = array('table' => EMAIL_CRON . ' as cron',
            'select' => 'user.id,user.email,cron.type,cron.type_id,cron.id as cron_id,user.name',
            'join' => array(USERS . ' as user' => 'user.id=cron.user_id'),
            'where' => array('cron.is_sent' => 0)
        );
        $users_email = $this->Common_model->customGet($option);
        if (!empty($users_email)) {
            foreach ($users_email as $email) {
                $from = FROM_EMAIL;
                $data['content'] = "Congratulation! You have added new loyalty point.";
                $data['user'] = ucwords($email->name);
                $message = $this->load->view('email_template', $data, true);
                $title = "New Loyalty";
                $subject = "Nice n Easy added new loyalty";
                $email_to = $email->email;
                if (send_mail($message, $subject, $email_to, $from, $title)) {
                    $option = array('table' => EMAIL_CRON,
                        'where' => array('id' => $email->cron_id)
                    );
                    $users_email = $this->Common_model->customDelete($option);
                }
            }
        }
    }

}
