<ul class="nav navbar-nav">
    {*<li>
        <a href="#">
            <span class="fa fa-user"></span> {$employeeData.pseudo_admin|ucfirst}
        </a>
    </li>*}
    <li>
        <a href="{geturl}" title="Aller sur le site" class="targetblank">
            <span class="hidden-sm hidden-md hidden-lg"><span class="fa fa-arrow-right"></span></span> <span class="fa fa-desktop"></span><span class="hidden-ph hidden-xs"> Aller sur le site</span>
        </a>
    </li>
    <li>
        <a href="{geturl}/admin/index.php?controller=dashboard&logout=1">
            <span class="fa fa-power-off"></span>
        </a>
    </li>
</ul>