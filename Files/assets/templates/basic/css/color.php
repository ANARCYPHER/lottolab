<?php
header("Content-Type:text/css");
$color = "#f0f"; // Change your Color Here
$secondColor = "#ff8"; // Change your Color Here

function checkhexcolor($color){
    return preg_match('/^#[a-f0-9]{6}$/i', $color);
}

if (isset($_GET['color']) AND $_GET['color'] != '') {
    $color = "#" . $_GET['color'];
}

if (!$color OR !checkhexcolor($color)) {
    $color = "#336699";
}


function checkhexcolor2($secondColor){
    return preg_match('/^#[a-f0-9]{6}$/i', $secondColor);
}

if (isset($_GET['secondColor']) AND $_GET['secondColor'] != '') {
    $secondColor = "#" . $_GET['secondColor'];
}

if (!$secondColor OR !checkhexcolor2($secondColor)) {
    $secondColor = "#336699";
}
?>

.pagination .page-item.active .page-link {
background-color: <?php echo $color ?>;
}
.pagination .page-item .page-link {
border: 1px solid <?php echo $color ?>40;
}
.pagination .page-item .page-link:hover {
background-color: <?php echo $color ?>;
border-color: <?php echo $color ?>;
}

.header .main-menu li a:hover, .header .main-menu li a:focus {
color: <?php echo $color ?>;
}

.btn--base {
background-color: <?php echo $color ?>;
}
.btn--base:hover {
background-color: <?php echo $color ?>;
}

a.text-white:hover {
color: <?php echo $color ?> !important;
}

.overview-wrapper {
background-color: #20204e;
box-shadow: 0 0 10px <?php echo $color ?>bf;
}

.text-shadow--base {
text-shadow: 0 0 5px <?php echo $color ?>;
}
.text--base {
color: <?php echo $color ?> !important;
}

.cumtom--nav-tabs .nav-item .nav-link, .custom--accordion-two .accordion-button:not(.collapsed), .custom--field i, .overview-card__icon, .inline-menu li a:hover, .contact-info i, .contact-info p a:hover {
color: <?php echo $color ?>;
}

.cumtom--nav-tabs .nav-item .nav-link.active, .custom--table thead, .inline-social-links li a:hover, .button-nav-tabs .nav-item .nav-link.active, .scroll-to-top {
background-color: <?php echo $color ?>;
}

.btn-outline--base {
color: <?php echo $color ?>;
border: 1px solid <?php echo $color ?>;
}

.preloader .preloader-container .animated-preloader {
background: <?php echo $color ?>;
}
.preloader .preloader-container .animated-preloader:before {
background: <?php echo $color ?>;
}

.lottery--progress .progress-bar {
background-color: <?php echo $color ?>;
}

.btn-outline--base:hover {
background-color: <?php echo $color ?>;
}

.how-work-card__step {
box-shadow: 0 0 10px <?php echo $color ?>8c;
}

.how-work-card__step::before {
background-color: <?php echo $color ?>;
}

.ticket-card__body .numbers.active span {
color: <?php echo $color ?>;
text-shadow: 0 2px 5px <?php echo $color ?>;
}

.stat-card {
box-shadow: inset 0 0 10px <?php echo $color ?>d9;
}

.feature-card {
box-shadow: inset 0 0 15px <?php echo $color ?>d9;
}
.feature-card:hover {
box-shadow: 0 5px 15px <?php echo $color ?>40, inset 0 0 15px <?php echo $color ?>d9;
border-color: <?php echo $color ?>;
}

.custom--accordion .accordion-item {
border: 1px solid <?php echo $color ?>80;
}

.custom--accordion .accordion-button:not(.collapsed) {
background-color: <?php echo $color ?>;
}

.winner-card {
border: 1px solid <?php echo $color ?>73;
box-shadow: inset 0 0 10px <?php echo $color ?>d9;
}
.winner-card:hover {
box-shadow: 0 5px 15px <?php echo $color ?>40, inset 0 0 15px <?php echo $color ?>d9;
border-color: <?php echo $color ?>;
}

.testimonial-item {
border: 3px solid <?php echo $color ?>;
}

.subscribe-wrapper {
box-shadow: 0 0 10px <?php echo $color ?>80;
}

.subscribe-form .form--control {
border-bottom: 1px solid <?php echo $color ?>80;
}

.form--control:focus {
border-color: <?php echo $color ?> !important;
}

.lottery-details-header .content .clock.disabled {
color: <?php echo $color ?>;
}
.lottery-details-header .content .clock > div {
box-shadow: inset 0 0 10px <?php echo $color ?>f9;
}
.lottery-details-header .content .clock > div span {
color: <?php echo $color ?>;
}

.account-wrapper,
.lottery-details-header {
--shadow-1: <?php echo $color ?>25;
--shadow-2: <?php echo $color ?>35;
--shadow-3:  <?php echo $color ?>45;
}

.cumtom--nav-tabs .nav-item .nav-link.active {
border-color: <?php echo $color ?>;
}
.cumtom--nav-tabs .nav-item .nav-link {
border: 1px solid <?php echo $color ?>;
}

.blog-card {
box-shadow: 0 0 0px 2px <?php echo $color ?>54;
}

.blog-card__meta li i {
color: <?php echo $color ?>;
}

.blog-details__thumb .post__date .date {
background-color: <?php echo $color ?>;
}

.sidebar .widget-title::before {
background-color: <?php echo $color ?>;
}

a:hover {
color: <?php echo $color ?>;
}

.page-breadcrumb li:first-child::before {
color: <?php echo $color ?>;
}

.page-breadcrumb li a:hover {
color: <?php echo $color ?>;
}

.account-wrapper {
border: 2px solid <?php echo $color ?>8c;
}

.form--control {
border: 1px solid <?php echo $color ?>;
}

.input-group-text {
background-color: <?php echo $color ?> !important;
border: 1px solid <?php echo $color ?> !important;
}

.balance-card {
background-color: <?php echo $color ?>;
}
.dashboard-card .number {
color: <?php echo $color ?>;
}

.dashboard-card .icon {
color: <?php echo $color ?>;
}

.header .main-menu li .sub-menu li a:hover {
background-color: <?php echo $color ?>0d;
color: <?php echo $color ?>;
}

.header .main-menu li.menu_has_children:hover > a::before {
color: <?php echo $color ?>;
}

.custom__bg {
box-shadow: 0 0 10px <?php echo $color ?>80;
}

.custom--file-upload ~ label {
background-color: <?php echo $color ?>;
}

.profile-thumb .avatar-edit label {
background-color: <?php echo $color ?>;
}

.lottery-details-body .top-part .middle .balance {
color: <?php echo $color ?>;
}

.ticket-card .ticket-card-del {
background-color: <?php echo $color ?>;
}