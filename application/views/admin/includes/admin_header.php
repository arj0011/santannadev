<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="<?php echo SITE_NAME; ?>">
        <meta name="keywords" content="<?php echo SITE_NAME; ?>">
        <title><?php echo SITE_NAME; ?> Admin</title>
        <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/css/animate.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/css/plugins/dropzone/basic.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/css/plugins/dropzone/dropzone.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/plugins/summernote/dist/summernote.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/css/ply.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/3.5.2/select2.css" rel="stylesheet"/>
        <link href="<?php echo base_url(); ?>assets/plugins/summernote/dist/summernote.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/jquery-ui.css">
        <link href="<?php echo base_url(); ?>assets/css/plugins/datapicker/datepicker4.css" rel="stylesheet"  type="text/css">
        <!--Add By Arjun -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/custom.css" />
        <!--end --> 
        <style>.error{color: #DC2430;}</style>
        <style>
            
        </style>
    </head>
    <body>
        <div id="wrapper">
            <nav class="navbar-default navbar-static-side" role="navigation">
                <div class="sidebar-collapse">
                    <ul class="nav metismenu" id="side-menu">
                        <li class="nav-header">

                            <div class="dropdown profile-element"> <span>
                                    <?php $logo = CommonGet(array('table' => ADMIN, 'single' => true)); ?>
                                    <img  width="100" alt="image" class="" src="<?php echo base_url().DEFAULT_LOGO; ?>" />
        <!--                                 <img width="80" alt="image" class="img-circle" src="<?php
                                    if (!empty($logo->company_logo)) {
                                        echo base_Url()
                                        ?>uploads/app/<?php
                                        echo $logo->company_logo;
                                    } else {
                                        echo base_url() . DEFAULT_LOGO;
                                    }
                                    ?>" />-->
                                </span>
                                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                    <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">
                                            </strong><?php
                                            if ($this->session->userdata('id') != '' && $this->session->userdata('role') == 'admin'){
                                             echo "Admin";
                                            }
                                            else if($this->session->userdata('id') != '' && $this->session->userdata('role') == 'agent'){
                                            echo $this->session->userdata('full_name');
                                            }
                                            else if($this->session->userdata('id') != '' && $this->session->userdata('role') == 'store'){

                                                echo $this->session->userdata('store_name');
                                            } 
                                            ?>  <b class="caret"></b>
                                        </span>  </span> </a>
                                <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                    <li><a href="<?php echo site_url('admin/changepassword'); ?>"><?php echo lang('change_password'); ?></a></li>
                                    <li class="divider"></li>
                                    <li><a href="<?php echo site_url('admin/logout'); ?>"><?php echo lang('logout'); ?></a></li>
                                </ul>
                            </div>
                            <div class="logo-element">
                                <img width="70" alt="image" class="" src="<?php echo base_url().DEFAULT_LOGO; ?>" />
                            </div>


                        </li>
                        <li title="<?php echo lang('dashboard'); ?>" class="<?php
                        if (!empty($parent) && $parent == 'dashboard'):echo 'active';
                        endif;
                        ?>">
                            <a href="<?php echo site_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> <span class="nav-label"><?php echo lang('dashboard'); ?></span></a>
                        </li>

                        <li title="<?php echo lang('booking'); ?>" class="<?php echo (strpos($parent, "booking") !== false || strpos($parent, "Booking") !== false) ? "active" : "" ?>">
                            <a href="#"><i class="fa fa-file-text-o"></i> <span class="nav-label"><?php echo lang('booking'); ?></span><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li title="<?php echo lang('view_booking'); ?>" class="<?php echo (strpos($parent, "View Booking") !== false) ? "active" : "" ?>">
                                    <a href="<?php echo site_url('tablebooking/view_booking'); ?>"><i class="fa fa-file-text-o"></i> <span class="nav-label"><?php echo lang('view_booking'); ?></span></a>
                                </li>   
                                <?php //if($this->session->userdata('id') != '' && $this->session->userdata('role') == 'admin'):   ?>
                                <li title="<?php echo lang('manage_booking'); ?>" class="<?php echo (strpos($parent, "manage booking") !== false) ? "active" : "" ?>">
                                    <a href="<?php echo site_url('tablebooking'); ?>"><i class="fa fa-table"></i> <span class="nav-label"><?php echo lang('manage_booking'); ?></span></a>
                                </li>
                                <li title="<?php echo "Delete History"; ?>" class="<?php echo (strpos($parent, "delete booking") !== false) ? "active" : "" ?>">
                                    <a href="<?php echo site_url('tablebooking/deleteHistory'); ?>"><i class="fa fa-trash"></i> <span class="nav-label"><?php echo "Delete History"; ?></span></a>
                                </li>
                                <?php //endif;   ?>

                            </ul>
                        </li>
                        
                        
                        <?php if ($this->session->userdata('id') != '' && $this->session->userdata('role') == 'admin'): ?>
                            
                                                        <li title="<?php echo lang('service'); ?>" class="<?php echo (strpos($parent, "restaurant") !== false) ? "active" : "" ?>">
                                                            <a href="<?php echo site_url('restaurant'); ?>"><i class="fa fa-cutlery"></i> <span class="nav-label"><?php echo lang('service'); ?></span></a>
                                                        </li>
                        <?php endif;if ($this->session->userdata('id') != '' && ($this->session->userdata('role') == 'admin' || $this->session->userdata('role') == 'store')): ?>
                                                        <li title="<?php echo lang('store'); ?>" class="<?php echo (strpos($parent, "store") !== false) ? "active" : "" ?>">
                                                            <a href="<?php echo site_url('store'); ?>"><i class="fa fa-cutlery"></i> <span class="nav-label"><?php echo lang('store'); ?></span></a>
                                                        </li>
                            
                                                        <li title="<?php echo lang('offer'); ?>" class="<?php echo (strpos($parent, "Offer") !== false) ? "active" : "" ?>">
                                                            <a href="<?php echo site_url('offer'); ?>"><i class="fa fa-file-text-o"></i> <span class="nav-label"><?php echo lang('offer'); ?></span></a>
                                                        </li>
                            
                                                        <li title="<?php echo lang('loyalty'); ?>" class="<?php echo (strpos($parent, "Loyalty") !== false) ? "active" : "" ?>">
                                                            <a href="<?php echo site_url('loyalty'); ?>"><i class="fa fa-file-text-o"></i> <span class="nav-label"><?php echo lang('loyalty'); ?></span></a>
                            
                                                        </li>


                                                            <li title="<?php echo lang('news'); ?>" class="<?php echo (strpos($parent, "news_category") !== false || strpos($parent, "News") !== false) ? "active" : "" ?>">
                                                                <a href="#"><i class="fa fa-newspaper-o"></i> <span class="nav-label"><?php echo lang('news'); ?></span><span class="fa arrow"></span></a>
                                                                <ul class="nav nav-second-level">

                                                                    <li class="<?php echo (strpos($parent, "news_category") !== false) ? "active" : "" ?>"><a  href="<?php echo site_url('news'); ?>"><?php echo lang('news'); ?></a></li>
                                                                    <li class="<?php echo (strpos($parent, "News") !== false) ? "active" : "" ?>"><a  href="<?php echo site_url('newsCategory'); ?>"><?php echo lang('news_category'); ?></a></li>
                                                                </ul>
                                                            </li>

                        <?php endif; ?>
                                                        <li title="<?php echo lang('menu'); ?>" class="<?php echo (strpos($parent, "menu_category") !== false || strpos($parent, "Menu") !== false || strpos($parent, "menu_subcategory") !== false) ? "active" : "" ?>">
                                                            <a href="#"><i class="fa fa-file-text-o"></i> <span class="nav-label"><?php echo lang('menu'); ?></span><span class="fa arrow"></span></a>
                                                            <ul class="nav nav-second-level">
                            
                                                                <li class="<?php echo (strpos($parent, "Menu") !== false) ? "active" : "" ?>"><a  href="<?php echo site_url('menus'); ?>"><?php echo lang('menu'); ?></a></li>
                            
                                                                <li class="<?php echo (strpos($parent, "menu_category") !== false) ? "active" : "" ?>"><a  href="<?php echo site_url('menuCategory'); ?>"><?php echo lang('menu_category'); ?></a></li>
                            
                                                                <li class="<?php echo (strpos($parent, "menu_subcategory") !== false) ? "active" : "" ?>"><a  href="<?php echo site_url('menuSubcategory'); ?>"><?php echo lang('menu_subcategory'); ?></a></li>
                            
                                                            </ul>
                                                        </li>

                        <?php if($this->session->userdata('id') != '' && ($this->session->userdata('role') == 'admin' || $this->session->userdata('role') == 'store')): ?>
                                                            <li title="<?php echo lang('cms'); ?>" class="<?php echo (strpos($parent, "Cms") !== false) ? "active" : "" ?>">
                                                                <a href="<?php echo site_url('cms'); ?>"><i class="fa fa-pagelines"></i> <span class="nav-label"><?php echo lang('cms'); ?></span></a>

                                                            </li>



                                                            <li title="<?php echo lang('servicefeedback'); ?>" class="<?php echo (strpos($parent, "servicefeedback") !== false) ? "active" : "" ?>">
                                                                <a href="<?php echo site_url('serviceFeedback'); ?>"><i class="fa fa-envelope"></i> <span class="nav-label"><?php echo lang('servicefeedback'); ?></span></a>
                                                            </li>

                                                            <li title="<?php echo lang('contactfeedback'); ?>" class="<?php echo (strpos($parent, "contactfeedback") !== false) ? "active" : "" ?>">
                                                                <a href="<?php echo site_url('contactFeedback'); ?>"><i class="fa fa-phone"></i> <span class="nav-label"><?php echo lang('contactfeedback'); ?></span></a>
                                                            </li>





                                <!--                            <li title="<?php echo lang('app_content'); ?>" class="<?php echo (strpos($parent, "app_info") !== false || strpos($parent, "App Content") !== false) ? "active" : "" ?>">
                                    <a href="#"><i class="fa fa-file-text-o"></i> <span class="nav-label"><?php echo lang('app'); ?></span><span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level">

                                        <li class="<?php echo (strpos($parent, "App Content") !== false) ? "active" : "" ?>"><a  href="<?php echo site_url('appinfo'); ?>"><?php echo lang('app_content'); ?></a></li>

                                        <li class="<?php echo (strpos($parent, "app_info") !== false) ? "active" : "" ?>"><a  href="<?php echo site_url('appinfo/info'); ?>"><?php echo lang('app_info'); ?></a></li>

                                    </ul>
                                </li>-->
                        <?php endif; ?>


                        <!--  </li> -->
                        <?php if ($this->session->userdata('id') != '' && ($this->session->userdata('role') == 'admin' || $this->session->userdata('role') == 'store')): ?>
                            <li title="<?php echo "Configuration"; ?>" class="<?php echo (strpos($parent, "Referrer") !== false || strpos($parent, "specialRequest") !== false || strpos($parent, "Group") !== false || strpos($parent, "User") !== false || strpos($parent, "Floorss") !== false || strpos($parent, "Agent") !== false || strpos($parent, "Floor") !== false || strpos($parent, "Table") !== false ) ? "active" : "" ?>">
                                <a href="#"><i class="fa fa-cogs"></i> <span class="nav-label"><?php echo "Configuration"; ?></span><span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">

                                    <li title="Floor" class="<?php echo (strpos($parent, "Floor") !== false || strpos($parent, "Table") !== false) ? "active" : "" ?>">
                                        <a href="<?php echo site_url('floors'); ?>"><i class="fa fa-building"></i> <span class="nav-label"><?php echo lang('locations'); ?></span></a>
                                    </li>

                                    <li title="<?php echo lang('agents'); ?>" class="<?php echo (strpos($parent, "Agent") !== false) ? "active" : "" ?>">
                                        <a href="<?php echo site_url('agents'); ?>"><i class="fa fa-user"></i> <span class="nav-label"><?php echo lang('agents'); ?></span></a>
                                    </li>

                                    <li title="<?php echo lang('user_category'); ?>" class="<?php echo (strpos($parent, "User") !== false) ? "active" : "" ?>">
                                        <a href="<?php echo site_url('users'); ?>"><i class="fa fa-user"></i> <span class="nav-label"><?php echo lang('user'); ?></span></a>
                                    </li>

                                    <li title="<?php echo lang('group'); ?>" class="<?php echo (strpos($parent, "Group") !== false) ? "active" : "" ?>">
                                        <a href="<?php echo site_url('group'); ?>"><i class="fa fa-users"></i> <span class="nav-label"><?php echo lang('group'); ?></span></a>

                                    </li>
                                    <li title="<?php echo lang('specialRequest'); ?>" class="<?php echo (strpos($parent, "specialRequest") !== false) ? "active" : "" ?>">
                                        <a href="<?php echo site_url('specialRequest'); ?>"><i class="fa fa-random"></i> <span class="nav-label"><?php echo lang('specialRequest'); ?></span></a>
                                    </li>

                                    <li title="<?php echo lang('referrer'); ?>" class="<?php echo (strpos($parent, "Referrer") !== false) ? "active" : "" ?>">
                                        <a href="<?php echo site_url('referrer'); ?>"><i class="fa fa-users"></i> <span class="nav-label"><?php echo lang('referrer'); ?></span></a>
                                    </li>

                                    <li class="<?php echo (strpos($parent, "app_info") !== false) ? "active" : "" ?>"><a  href="<?php echo site_url('appinfo'); ?>"> <i class="fa fa-cogs"></i>App Setting</a></li>
                                <?php 
                                endif;
                                if ($this->session->userdata('id') != '' && $this->session->userdata('role') == 'admin'): ?>
                                    <li title="<?php echo lang('point_config'); ?>" class="<?php echo (strpos($parent, "point_config") !== false) ? "active" : "" ?>"><a  href="<?php echo site_url('pointconfig'); ?>"> <i class="fa fa-cogs"></i><?php echo lang('point_config'); ?></a></li>
                                <?php endif;
                                if ($this->session->userdata('id') != '' && ($this->session->userdata('role') == 'admin' || $this->session->userdata('role') == 'store')): ?>                                    
    <!--                                <li title="<?php echo lang('notification'); ?>" class="<?php echo (strpos($parent, "notification") !== false) ? "active" : "" ?>">
                                        <a href="<?php echo site_url('notification'); ?>"><i class="fa fa-bell"></i> <span class="nav-label"><?php echo lang('notification'); ?></span></a>
                                    </li>-->

                                </ul>

                            </li>
                            
                            <li title="<?php echo lang('floor_notification'); ?>" class="<?php if (!empty($parent) && $parent == 'floor_notification'):echo 'active';endif;?>">
                             <a href="<?php echo site_url('notification/push_message'); ?>"><i class="fa fa-paper-plane-o"></i> <span class="nav-label"><?php echo lang('floor_notification'); ?></span></a>
                            </li>
                        <?php endif; ?>
                            <li title="<?php echo lang('reports'); ?>" class="">
                                <a href="#"><i class="fa fa-globe"></i> <span class="nav-label"><?php echo lang('reports'); ?></span><span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                  <li title="<?php echo lang('client'); ?>" class="">
                                        <a href="javascript:void(0)"><i class="fa fa-globe"></i> <span class="nav-label"><?php echo lang('client'); ?></span>
                                        <span class="label label-info pull-right"><?php echo getAllCount(USERS); ?></span></a>
                                    </li>
                                    <li title="<?php echo lang('booking'); ?>" class="">
                                        <a href="javascript:void(0)"><i class="fa fa-globe"></i> <span class="nav-label"><?php echo lang('booking'); ?></span>
                                        <span class="label label-warning pull-right"><?php echo getAllCount('mw_booking'); ?></span></a>
                                    </li>
                                    <li title="<?php echo lang('today_booking'); ?>" class="">
                                        <a href="javascript:void(0)"><i class="fa fa-globe"></i> <span class="nav-label"><?php echo lang('today_booking'); ?></span>
                                        <span class="label label-primary pull-right"><?php if ($this->session->userdata('role') == 'admin'): ?>
                                            <?php echo getAllCount('mw_booking', array('booking_date' => date('Y-m-d'))); ?>
                                            <?php
                                            else:
                                                echo getAllCount('mw_booking', array('booking_date' => date('Y-m-d'), 'agent_id' => $this->session->userdata('id')));
                                            endif;
                                            ?></span></a>
                                    </li>
                                    <li title="<?php echo lang('agents'); ?>" class="">
                                        <a href="javascript:void(0)"><i class="fa fa-globe"></i> <span class="nav-label"><?php echo lang('agents'); ?></span>
                                        <span class="label label-success pull-right"><?php echo getAllCount(AGENTS); ?></span></a>
                                    </li>
                                    <li title="Booking Export">
                                        <a href="<?php echo base_url() . 'admin/exportExcelBooking/all' ?>"><i class="fa fa-file-excel-o"></i> <span class="nav-label">Booking Export</span></a>
                                    </li>
                                     <li title="Today Booking Export">
                                        <a href="<?php echo base_url() . 'admin/exportExcelBooking/today' ?>"><i class="fa fa-file-excel-o"></i> <span class="nav-label">Today Booking Export</span></a>
                                    </li>
<!--                                    <li title="Booking Walk">
                                       <a href="<?php //echo base_url() . 'admin/current_booking' ?>"><i class="fa fa-id-badge"></i> <span class="nav-label"><?php echo lang('walk_in'); ?></span></a>
                                   </li>-->
                                </ul>
                            </li>
                           
                            <li title="Language" class="">
                                <a href="#"><i class="fa fa-language"></i> <span class="nav-label">Language</span><span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <?php $languages = CommonGet(array('table' => LANGUAGE)); ?>
                                    <?php foreach ($languages as $rows): ?>
                                    <li title="Translate In <?php echo $rows->language_name; ?>" class="<?php echo ($rows->is_default == 1) ? "active" : ""; ?>">
                                           <a href="javascript:void(0)" onclick="changeLanguage('<?php echo $rows->language_code; ?>')"><i class="fa fa-language"></i> <span class="nav-label">Translate In <?php echo $rows->language_name; ?></span></a>
                                       </li>
                                   <?php endforeach; ?>
                                </ul>
                            </li>
                            <li title="Logout">
                                <a href="<?php echo site_url('admin/logout'); ?>"><i class="fa fa-sign-out"></i> <span class="nav-label text-danger"><?php echo lang('logout'); ?></span></a>
                            </li>
                    </ul>
                </div>
            </nav>
            <input type="hidden" value="" id="latestOrderId">
            <div id="page-wrapper" class="gray-bg">
                <div class="row border-bottom">
                    <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0; z-index: 9999;">
                        <div class="navbar-header">
                            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="javascript:void(0)"><i class="fa fa-bars"></i> </a>
                        </div>
                        <ul class="nav navbar-top-links navbar-right">

<!--                            <li class="dropdown" id="notification-list-view">

                            </li>-->
<!--                            <li><a style="display:none;" id="exitFullScreen" href="javascript:void(0)" onclick="ExitfullScreen()"><img  width="30" alt="Exit Full Screen" class="" src="<?php echo base_url(); ?>assets/img/exit-full-screen-arrows.png"  title="Exit Full Screen"/></a></li>
                            <li><a href="javascript:void(0)" onclick="fullScreen()"><img  width="30" alt="Full Screen" class="" src="<?php echo base_url(); ?>assets/img/switch-to-full-screen-button.png"  title="Full Screen"/></a></li>-->
<!--                            <li>
                                <div><select class="form-control" id="change-language" onchange="changeLanguage(this.value)">
                                        <?php //$languages = CommonGet(array('table' => LANGUAGE)); ?>
                                        <?php //foreach ($languages as $rows): ?>
                                            <option value="<?php //echo $rows->language_code; ?>" <?php //echo ($rows->is_default == 1) ? "selected" : ""; ?>><?php //echo $rows->language_name; ?></option>
                                        <?php //endforeach; ?>
                                    </select></div>
                            </li>-->
<!--                            <li>
                                <a href="<?php echo site_url('admin/logout'); ?>">
                                    <span class="btn btn-sm btn-danger"><i class="fa fa-sign-out"></i> <?php echo lang('logout'); ?></span>
                                </a>
                            </li>-->
                        </ul>

                    </nav>
                </div>
                <div class="row wrapper border-bottom white-bg page-heading custom_pgheading">
                <div class="col-lg-12">
                    <ol class="breadcrumb col-md-offset-1">
                        <li>
                            <a href="<?php echo site_url('admin/dashboard'); ?>"><?php echo lang('home'); ?></a>
                        </li>
                        <li>
                            <a href="<?php if(isset($url)){echo $url;}?>"><?php if(isset($pageTitle)){echo $pageTitle;}?></a>
                        </li>
                    </ol>
                    <ul class="nav navbar-top-links navbar-right custom_message">
                        <li><span class="text-info"><i class="fa fa-clock-o"></i></span> <span id="timeTable"></span></li>
                        <li class="dropdown" id="notification-list-view">
                        </li>
                        <?php if ($this->session->userdata('id') != '' && $this->session->userdata('role') == 'admin'): ?> 
                        <li><a href="javascript:void(0)" data-toggle="modal" data-target="#notificationModal" title="Send Floor Manager Push Message"><i class="fa fa-send"></i></a></li>
                        <?php endif;?>
                        <li><a style="display:none;" id="exitFullScreen" href="javascript:void(0)" onclick="ExitfullScreen()"><img  width="30" alt="Exit Full Screen" class="" src="<?php echo base_url(); ?>assets/img/exit-full-screen-arrows.png"  title="Exit Full Screen"/></a></li>
                        <li><a href="javascript:void(0)" onclick="fullScreen()"><img  width="30" alt="Full Screen" class="" src="<?php echo base_url(); ?>assets/img/switch-to-full-screen-button.png"  title="Full Screen"/></a></li>
                        <li><a href="<?php echo site_url('admin/logout'); ?>" title="Logout"> <span class="nav-label text-danger"><i class="fa fa-sign-out fa-lg"></i></span></a></li>
                    </ul>
                </div>
            </div>

