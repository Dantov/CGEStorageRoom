<?php

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use app\models\{User,Common};
use app\models\serviceClasses\MyStore;

AppAsset::register($this);

$this->registerCsrfMetaTags();

$session = Yii::$app->session;
$controller    = $this->context;
$projects      = $controller->projects;
$projectName    = $controller->projectName;
$nonPublished  = $controller->nonPublished;
$allHashtags   = $controller->hashtags;
$allCategories = $controller->categories;
$allStorageRooms = $controller->storagerooms;
$totC = '';
if (isset($controller->totalCount))
    $totC = '('.$controller->totalCount.')';

$searchFor = $session->has('searchFor')?$session->get('searchFor') : '';

$hashtags = $session->get('selectByHashtags');
foreach( $allHashtags as &$singlehashtag ){
    if ( in_array($singlehashtag['name'], $hashtags) ){
        $singlehashtag['active'] = true;
    }
}
unset($singlehashtag);

$this->registerJs($controller->jsCONSTANTS,View::POS_HEAD);
?>
<!doctype html>
<?php $this->beginPage() ?>
<html lang="<?= Yii::$app->language ?>">
<head>
    <!-- Required meta tags -->
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="../images/favicon.ico?ver3=<?=time()?>">
    <script src="../js/const.js?ver=<?=time()?>"></script>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body>
<?php $this->beginBody() ?>

<div class="wrapper">
    <!-- Sidebar Holder START -->
    <nav id="sidebar">
        <div class="sidebar-header text-center">
            <h1>
                <a href="<?=Url::to(['/site'])?>"><img src="/images/CGEicon2.png" height="70px" class="">
                    <h5 class="">CGE Storage Room</h5>
                </a>
            </h1>
        </div>
        <ul class="list-unstyled components">
            <li class="activeSB">
                <a href="#showSubmenu1" data-toggle="collapse" data-closed="true" aria-expanded="false" class="sidebarMenuA">
                    <i class="fas fa-th-large"></i>
                    Storage Base
                    <i class="fas fa-angle-left fa-pull-right"></i>
                </a>
                <ul class="collapse list-unstyled" id="showSubmenu1">
                    <?php if ( User::hasPermission('add_model') ):?>
                        <li><a href="<?=Url::to(['/site/add'])?>"><i class="far fa-file"></i> Create record</a></li>
                    <?php endif; ?>
                    <li><a href="<?=Url::to(['/search/select','by'=>'purgeall'])?>"><i class="fas fa-th-large"></i> Show by tiles</a></li>
                    <li><a href="<?=Url::to(['/site'])?>"><i class="far fa-edit"></i> Select mode</a></li>
                    <li><a href="<?=Url::to(['/site'])?>"><i class="far fa-file-alt"></i> Export to PDF</a></li>
                    <?php if ( count($nonPublished) ): ?>
                    <li>
                        <?php $nonPubactive = $session->get('SelectByNonPub')?"bg-secondary":"" ?>
                        <a class="<?=$nonPubactive?>" href="<?=Url::to(['/search/select','by'=>'nonpub'])?>">
                        <i class="fa-solid fa-envelopes-bulk"></i> Non Published</a>
                    </li>
                    <?php endif; ?>
                    <?php if ( User::isAdmin() ): ?>
                    <li>
                        <?php $dellactive = $session->get('SelectByDeleted')?"bg-secondary":"" ?>
                        <a class="<?=$dellactive?>" href="<?=Url::to(['/search/select','by'=>'deleted'])?>"><i class="fa-solid fa-ban"></i> Deleted</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </li>
            <li>
                <a href="#sortSubmenu1" data-toggle="collapse" data-closed="true" aria-expanded="false" class="sidebarMenuA">
                    <i class="far fa-window-restore"></i>
                    Sort
                    <i class="fas fa-angle-left fa-pull-right"></i>
                </a>
                <ul class="collapse list-unstyled" id="sortSubmenu1">
                    <li>
                        <a href="#positionsSubmenu1" data-toggle="collapse" data-closed="true" aria-expanded="false" class="sidebarMenuA">
                            <i class="fas fa-th"></i>
                            <span>Positions: <?=$session->get('positionsCount')?></span>
                            <i class="fas fa-angle-left fa-pull-right"></i>
                        </a>
                        <ul class="collapse list-unstyled" id="positionsSubmenu1">
                            <li>
                                <a href="<?=Url::to(['/search/positions-count','v'=>27])?>">27</a>
                            </li>
                            <li>
                                <a href="<?=Url::to(['/search/positions-count','v'=>54])?>">54</a>
                            </li>
                            <li>
                                <a href="<?=Url::to(['/search/positions-count','v'=>108])?>">108</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#modeltypeSubmenu1" data-toggle="collapse" data-closed="true" aria-expanded="false" class="sidebarMenuA">
                            <i class="fa-solid fa-swatchbook"></i>
                            By Category: <?=$session->get('selectByCategory')?$session->get('selectByCategory'):"Clean"?>
                            <i class="fas fa-angle-left fa-pull-right"></i>
                        </a>
                        <ul class="collapse list-unstyled" id="modeltypeSubmenu1">
                            <li><a href="<?= Url::to(['/search/select','by'=>'category','v'=>123])?>">Clean</a></li>
                            <?php foreach( $allCategories as $singleCat ): ?>
                                <li>
                                    <a class="pt-2 pb-2" href="<?= Url::to(['/search/select','by'=>'category','v'=>$singleCat['name']])?>">
                                        &nbsp;&nbsp;<i class="fa-solid fa-ellipsis"></i><?=$singleCat['name']?>
                                        <?php if ( $session->get('selectByCategory') == $singleCat['name'] ): ?>
                                            &nbsp;&nbsp;<i class="fa-solid fa-check"></i>
                                        <?php endif; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li>
                        <a href="#hashtagSubmenu1" data-toggle="collapse" data-closed="true" aria-expanded="false" class="sidebarMenuA">
                            <i class="fa-solid fa-tags"></i>
                            By Tag: <?=$session->get('selectByHashtags')?' <i class="fa-solid fa-check"></i>':"Clean" ?>
                            <i class="fas fa-angle-left fa-pull-right"></i>
                        </a>
                        <ul class="collapse list-unstyled" id="hashtagSubmenu1">
                            <li><a href="<?= Url::to(['/search/select/','by'=>'hashtag','v'=>123])?>">Clean</a></li>
                            <?php foreach( $allHashtags as $singlehashtag ): ?>
                                <li>
                                    <a class="pt-2 pb-2" href="<?= Url::to(['/search/select/','by'=>'hashtag','v'=>$singlehashtag['name']])?>">
                                        &nbsp;&nbsp;<i class="fa-solid fa-ellipsis"></i><?=$singlehashtag['name']?>

                                        <?php if (isset($singlehashtag['active'])): ?>
                                            &nbsp;&nbsp;<i class="fa-solid fa-check"></i>
                                        <?php endif;?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li>
                        <a href="#bySubmenu" data-toggle="collapse" data-closed="true" aria-expanded="false" class="sidebarMenuA">
                            <i class="far fa-calendar-alt"></i>
                            By Date: <?=$session->get('selectFromDate')?$session->get('selectFromDate'):"Clean" ?>
                            <i class="fas fa-angle-left fa-pull-right"></i>
                        </a>
                        <ul class="collapse list-unstyled" id="bySubmenu">
                            <li>
                                <a href="<?=Url::to(['/search/select/','by'=>'purgedate'])?>">Clean</a>
                            </li>
                            <li>
                                <a class="cursorPointer">С &nbsp;&nbsp;<input class="bg-dark text-light" type="date" id="createdatefrom" value="<?=$session->get('selectFromDate')?>"/></a>
                            </li>
                            <li>
                                <a class="cursorPointer">По &nbsp;&nbsp;<input class="bg-dark text-light" type="date" id="createdateto" value="<?=$session->get('selectToDate')?>"/></a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#growingSubmenu" data-toggle="collapse" data-closed="true" aria-expanded="false" class="sidebarMenuA">
                            <i class="fas fa-sort-amount-up-alt"></i>
                            By: <?=($session->get('selectByOrder')===SORT_ASC)?"Oldest":"Newest"?>
                            <i class="fas fa-angle-left fa-pull-right"></i>
                        </a>
                        <ul class="collapse list-unstyled" id="growingSubmenu">
                            <li>
                                <a href="<?=Url::to(['/search/select','by'=>'order','v'=>'ASC'])?>">Oldest</a>
                            </li>
                            <li>
                                <a href="<?=Url::to(['/search/select','by'=>'order','v'=>'DESC'])?>">Newest</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li>
                <?php if ( User::hasPermission('add_model') ):?>
                <a href="<?=Url::to(['/site/add'])?>"><i class="far fa-file"></i>Create record</a>
                <?php endif;?>
            </li>
            <li>
                <?php if ( User::hasPermission('nomenclature') ):?>
                    <a href="<?=Url::to(['/site/nomenclature'])?>">
                        <i class="far fa-list-alt"></i>
                        Nomenclature
                    </a>
                <?php endif;?>
            </li>
            <li>
                <a href="#noticesSubmenu" data-toggle="collapse" data-closed="true" aria-expanded="false" class="sidebarMenuA">
                    <i class="far fa-bell"></i>Notifications
                    <?php if ( count($nonPublished) ): ?>
                        <span class="badge badge-secondary bg-danger"><?=count($nonPublished)?> new</span>
                        <i class="fas fa-angle-left fa-pull-right"></i>
                    <?php endif; ?>
                </a>
                <ul class="collapse list-unstyled" id="noticesSubmenu">
                    <li>
                        <a class="bg-danger publishall" href=""><i class="fa-solid fa-stamp"></i>Publish All</a>
                    </li>
                    <?php foreach( $nonPublished as $npModel): ?>
                    <li>
                        <a href="<?=Url::to(['/site/edits','model'=>$npModel['id']])?>" class="p-2 border-bottom border-secondary">
                            <span>New record was added</span><br>
                            <span>For <?=htmlentities($npModel['project'])?></span><br>
                            <span class="text-warning">Non Published!</span><br>
                            <?php if ( empty($npModel['images']) ): ?>
                                <img src="/pictAssets/web1.webp" width="50px" class="mr-2">
                            <?php else: ?>
                                <?php $imgname = isset($npModel['previmg'])?$npModel['previmg']:$npModel['mainimage'] ?>
                                <img src="<?=Url::to('/stock/'.Common::modelPath($npModel['project'],$npModel['id']).'/images/'.$imgname)?>" width="50px" class="mr-2">
                            <?php endif; ?>
                            <span><?=$npModel['item_name']?></span><br>
                            <span>Added by: <?=User::getUsernameByID($npModel['creator_id']). " - " .formatDate($npModel['date'])?></span>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </li>

        </ul>
    </nav>
    <!-- Sidebar END -->

    <!-- Page Content Holder -->
    <div id="content" class="pb-0">
        <!-- top-bar -->
        <nav class="navbar mb-2" style="margin: -10px -10px 0 -10px; display: block!important;">
            <div class="d-flex justify-content-between bd-highlight">
                <div class="p-1 bd-highlight">
                    <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn bg-dark">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
                <?php if ($controller->isDesktop): ?>
                <div class="p-1 bd-highlight" id="search-form">
                    <div class="pt-1 mx-auto">
                        <div class="input-group input-group-sm align-middle">
                            <div class="input-group-prepend">
                                <button title="Found" class="btn btn-outline-primary border-0"><?=$totC?></button>
                                <button title="Purge" id="purge_button" class="btn btn-outline-secondary border-0"><i class="fa-solid fa-broom"></i></button>
                                <button title="push for search" id="search_button" class="btn btn-outline-secondary border-0"><i class="fas fa-search"></i></button>
                            </div>
                            <input type="text" id="search_row" value="<?=$searchFor?>" type="search" placeholder="Search..." aria-label="Search" class="form-control border-top-0 border-left-0 border-right-0">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary border-0 dropdown-toggle" type="button" title="where to find" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-gem"></i>
                                    <span>
                                        <?= $projectName ?>
                                    </span>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" data-clientID="11" href="<?=Url::to(['/search/select','by'=>'project','v'=>11])?>">All Projects</a>
                                    <div class="dropdown-divider"></div>
                                    <?php foreach( $projects as $project ): ?>
                                    <?php $clname = User::hasPermission('hideclients')?$project['secondname']:$project['name'] ?>
                                    <a class="dropdown-item" data-clientID="<?=$project['id']?>" href="<?=Url::to(['/search/select','by'=>'project','v'=>$project['id'] ])?>"><?=htmlentities($clname)?>
                                    <?php if( in_array($project['name'],$session->get('SelectByProjects')??[] ) ):?>
                                        <span class="float-right"><i class="fa-solid fa-square-check"></i></span>
                                    <?php endif; ?>
                                    </a>
                                    <?php endforeach;?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif;?>
                <?php if( User::hasPermission('mybox')): ?>
                <div class="p-1 bd-highlight jewelboxTopbar">
                    <ul class="user-bar top-icons-agileits-w3layouts">
                        <li class="nav-item dropdown">
                            <a class="dropdown-toggle" style="" href="#" id="navbarDropdown2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php if( !User::isAdmin()): ?>
                                <span class="p-1 border border-dark bg-secondary text-light rounded-circle jbBadge"><?=MyStore::getModelsCount()?></span>
                                <?php endif; ?>
                                <?php $colormyboxicon = MyStore::getModelsCount()>0?"primary":"secondary"; ?>
                                <?php $mboxTitle = User::isAdmin()?"Common Box":"My Box"; ?>
                                <div class="profile-l mr-0 text-<?=$colormyboxicon?>" data-toggle="tooltip" title="<?=$mboxTitle?>">
                                    <h2><i class="fa-solid fa-box-open"></i></h2>
                                </div>
                            </a>
                            <div class="dropdown-menu drop-3">
                                <?php $whatinside=User::isAdmin()?MyStore::getModelsCount(true)." items inside":MyStore::getModelsCount()." item(s) inside"?>
                                <div class="profile-r align-self-center">
                                    <h5 class="sub-title-w3-agileits"><small><?=$whatinside?></small></h5>
                                </div>
                                <div class="dropdown-divider"></div>
                                <?php $uri = User::isAdmin() ? 'common' : 'show' ?>
                                <a href="<?=Url::to(['site/my','box'=>$uri])?>" class="dropdown-item mt-2">
                                    <h4><i class="far fa-gem mr-3"></i>Show</h4>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
                <?php endif;?>
                <div class="p-1 bd-highlight">
                    <ul class="user-bar top-icons-agileits-w3layouts">
                        <li class="nav-item dropdown">
                            <a class="dropdown-toggle" style="" href="#" id="navbarDropdown2" role="button" data-toggle="dropdown" aria-haspopup="true"
                               aria-expanded="false">
                                <div class="profile-l mr-0">
                                    <img src="/web/images/users/<?=User::getAvatar()?>" style="height:40px; object-fit: cover;" class="img-fluid" alt="Responsive image">
                                </div>
                            </a>
                            <div class="dropdown-menu drop-3">
                                <div class="profile-r align-self-center">
                                    <h3 class="sub-title-w3-agileits"><?=User::getFIO()?></h3>
                                </div>
                                <div class="dropdown-divider"></div>
                                <?php if(User::hasPermission('mybox')): ?>
                                    <a href="<?=Url::to(['site/my','box'=>'show'])?>" class="dropdown-item mt-2">
                                        <h4><i class="fa-solid fa-box-open"></i> My Box</h4>
                                    </a>
                                <?php endif;?>
                                <?php if(User::hasPermission('profile')): ?>
                                    <a href="<?=Url::to(['site/profile'])?>" class="dropdown-item mt-2">
                                        <h4><i class="far fa-user mr-3"></i>Profile</h4>
                                    </a>
                                <?php endif;?>
                                <?php if(User::hasPermission('options')): ?>
                                    <a href="<?=Url::to(['site/options'])?>" class="dropdown-item mt-2">
                                        <h4><i class="fas fa-tools mr-3"></i></i>Options</h4>
                                    </a>
                                <?php endif;?>
                                <?php if(User::hasPermission('statistic')): ?>
                                    <a href="<?=Url::to(['site/statistic'])?>" class="dropdown-item mt-2">
                                        <h4><i class="fas fa-chart-pie mr-3"></i>Statistic</h4>
                                    </a>
                                <?php endif;?>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?=Url::to(['auth/logout'])?>">Exit</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!--// top-bar -->

        <!-- jewel-box-modal -->
        <div class="modal fade" id="jewel-box-modal" tabindex="-1" aria-labelledby="jbModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h6 class="modal-title" id="jbModalLabel"></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 pb-0">
                        <tbody>
                            <tr align="center">
                                <td><img class="mjb-img" src="" width="80rem;"></td>
                                <td class="mjb-mtype"></td>
                                <td class="mjb-client"></td>
                                <td>
                                    <a class="mjb-link btn btn-success btn-sm" href="" role="button"><i class="fa-solid fa-eye"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="located-in">located in:</td>
                                <td colspan="2">
                                    <div class="form-group d-none storageRoomsbox">
                                        <label for="storageRoomsbox">Storage Room:</label>
                                        <select class="form-control" id="storageRoomsbox" value="">
                                        <?php foreach ($allStorageRooms as $stRoom): ?>
                                            <option style="cursor: pointer;"><?=$stRoom['name']?></option>
                                        <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-row roomboxlocated">
                                        <label for="roomboxlocated">Room:</label>
                                        <input type="text" class="form-control" id="roomboxlocated" value="" disabled>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group d-none inputShelfBox">
                                        <label for="inputShelfBox">Shelf:</label>
                                        <input type="text" class="form-control" id="inputShelfBox"> 
                                    </div>
                                    <div class="form-row shelfboxlocated">
                                        <label for="shelfboxlocated">Shelf:</label>
                                        <input type="text" class="form-control" id="shelfboxlocated" value="" disabled>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="mb-0 pb-0">
                                    <div class="form-group">
                                        <label for="commenttext">Comment</label>
                                        <textarea class="form-control" id="mjb-commenttext" rows="2"></textarea>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="mjb-submit" class="btn btn-primary">Add to My Box</button>
              </div>
            </div>
          </div>
        </div>

        <div class="container-fluid content" id="wrapp">
            <?php if ($controller->isMobile): ?>
            <div class="input-group input-group-sm align-middle">
                <div class="input-group-prepend">
                    <button title="Found" class="btn btn-outline-primary border-0"><?=$totC?></button>
                    <button title="Purge Query" id="purge_button" class="btn btn-outline-secondary border-0"><i class="fa-solid fa-broom"></i></button>
                    <button title="Push for search" id="search_button" class="btn btn-outline-secondary border-0"><i class="fas fa-search"></i></button>
                </div>
                <input type="text" id="search_row" value="<?=$searchFor?>" type="search" placeholder="Search..." aria-label="Search" class="form-control border-top-0 border-left-0 border-right-0">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary border-0 dropdown-toggle" type="button" title="where to search" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-gem"></i>
                        <span>
                            <?php //$showClname ?>
                        </span>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" data-clientID="11" href="<?=Url::to(['/search/select','by'=>'project','v'=>11])?>">All Projects</a>
                        <div class="dropdown-divider"></div>
                        <?php foreach( $projects as $project ):?>
                        <?php $clname = User::hasPermission('hideclients')?$project['secondname']:$project['name'] ?>
                            <a class="dropdown-item" data-clientID="<?=$project['id']?>" href="<?=Url::to(['/search/select','by'=>'project','v'=>$project['id'] ])?>"><?=htmlentities($clname) ?></a>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
            <?php endif;?>
            <?= $content; ?>
        </div>

        <!-- Copyright -->
        <div class="copyright-w3layouts shadow pt-2 pb-2 mt-2 text-center" style="bottom: 0 !important;" id="footer">
            <p class="float-left ml-3"><small>Developed by Vadym Bykov</small></p>
            <p class="float-right mr-3"> ver 0.0.3 alpha</p>
            <div class="clearfix"></div>
        </div>
        <!--// Copyright -->
    </div>
</div>
<div id="alertResponseModal" aria-hidden="true" aria-labelledby="alertResponseModal" role="dialog" class="iziModal">
    <div id="alertResponseContent" style="padding: 10px" class="hidden"></div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>