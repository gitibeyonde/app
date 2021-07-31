<?php
include (__ROOT__ . '/views/_header.php');
include_once (__ROOT__ . '/classes/core/Icons.php');
$Icon = new Icons ();

?>
<body>

<div class="container top">
<div class="row">
     <form class="form-inline"  action="/index.php"  method="get">
     <input type=hidden name=view value="<?php echo WORKFLOW_LISTING; ?>">
	 <button style="background: transparent; border: 0px;"><?php echo $Icon->get("arrow_left", "1.5", "blue"); ?></button>
	</form>
    <h4>Select catalog or required type and customize the template</h4>
</div>

<div class="row"  style="padding: 20px;">
     <div class="col-md-6 mb-3 mb-md-0">
        <div class="card py-4 h-100">
            <div class="card-body text-center">
                <h4 class="text-uppercase m-0">Basic E-catalog</h4>
                <hr class="my-4" />
                <div class="text-black-50">

                <p>Create beautiful catalogues. These catalogs are single page collection of images and text. Keep the images that you want
                to show to your customers via this medium ready with you. Using image tool upload the images. The WYSIWYG Html editor lets you arrange the images
                with text titles and description.</p>

                <p><a href="https://1do.in/7,MU0zS2NiVk" target="_blank">check raw template</a><br/>
                <a href="https://1do.in/spiceroute" target="_blank">user contributed: spiceroute</a></p>
                <a href="javascript:void(0)" onclick='pop_up("/catalog-maker/docs/quick_start.html");'><i class="ti-info" style="float: right;color: blue;font-size: 2rem;font-weight: bold;"></i></a>
                <h5><a href="/index.php?view=wiz_wf_desc&bot_id=0983838df9&submit=add">Customize the template</a></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 d-md-block d-sm-none d-none mb-3 mb-md-0">
        <div class="card py-4 h-100"   style="background-image: url(/catalog-maker/img/basic.png);background-size: cover;">
          <br/>
          <br/>
        </div>
     </div>
</div>


<div class="row"  style="padding: 20px;">
    <div class="col-md-6 d-md-block d-sm-none d-none mb-3 mb-md-0">
        <div class="card py-4 h-100"   style="background-color: black;background-image: url(/catalog-maker/img/survey.jpg);background-size: cover;">
          <br/>
          <br/>
        </div>
     </div>
     <div class="col-md-6 mb-3 mb-md-0">
        <div class="card py-4 h-100">
            <div class="card-body text-center">
                <h4 class="text-uppercase m-0">Simple Navigable Catalog</h4>
                <hr class="my-4" />
                <div class="text-black-50">This is a catalog with several pages of information that you can browse.
                Other than pages of information this catalog uses buttons. The buttons are created under "Actions"
                part of the editor. A page is know by its "state" name. A click on the button takes you to the linked page.
                If you create multiple buttons on a page they will be converted to a drop down of choices. Be ready with
                the navigation plan for your catalog when you start customizing the template.

                <p><a href="https://1do.in/7,TkFuenhCR0" target="_blank">Check raw template</a><br/>
                <a href="https://1do.in/7,dlkxREdwYk" target="_blank">Green Homes Catalog</a></p>
                <a href="javascript:void(0)" onclick='pop_up("/catalog-maker/docs/catalog_navigable.html");'><i class="ti-info" style="float: right;color: blue;font-size: 2rem;font-weight: bold;"></i></a>
                <h5><a href="/index.php?view=wiz_wf_desc&bot_id=d87cfda1a8&submit=add">Customize the template</a></h5>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row"  style="padding: 20px;">
     <div class="col-md-6 mb-3 mb-md-0">
        <div class="card py-4 h-100">
            <div class="card-body text-center">
                <h4 class="text-uppercase m-0">Data Driven Catalog</h4>
                <hr class="my-4" />
                <div class="text-black-50">This type of catalog is generated from the database of information.
                This database is called the catalogs knowledge-base (KB).
                The platform uses the KB to render pages. Be ready with the items, categories, description and images
                of the items when you start customizing this template.

                <p><a href="https://1do.in/7,alI2c2NIb2" target="_blank">Check raw template</a><br/>
                <a href="https://1do.in/7,Nk5XMlFRWl" target="_blank">Montblanc Catalog</a></p>
                <a href="javascript:void(0)" onclick='pop_up("/catalog-maker/docs/catalog_data.html");'><i class="ti-info" style="float: right;color: blue;font-size: 2rem;font-weight: bold;"></i></a>
                <h5><a href="/index.php?view=wiz_wf_desc&bot_id=e56c5758b9&submit=add">Customize the template</a></h5>
            </div>
          </div>
       </div>
    </div>
    <div class="col-md-6 d-md-block d-sm-none d-none mb-3 mb-md-0">
        <div class="card py-4 h-100" style="background-color: black;background-image: url(/catalog-maker/img/catalog.jpg);background-size: cover;">
          <br/>
          <br/>
        </div>
    </div>
</div>



<div class="row"  style="padding: 20px;">
    <div class="col-md-6 d-md-block d-sm-none d-none mb-3 mb-md-0">
        <div class="card py-4 h-100"   style="background-color: black;background-image: url(/catalog-maker/img/survey.jpg);background-size: cover;">
          <br/>
          <br/>
        </div>
     </div>
     <div class="col-md-6 mb-3 mb-md-0">
        <div class="card py-4 h-100">
            <div class="card-body text-center">
                <h4 class="text-uppercase m-0">Questionnaire or Survey</h4>
                <hr class="my-4" />
                <div class="text-black-50"> A Survey lets you capture data.
                With validation enabled the data is automatically mapped to the OTP medium ie, either email or mobile number.
                Wide variety of questions with text answers or multiple choices can be built into survey.
                The responses are available in your Dashboard.

                <a href="https://1do.in/7,V3BRa1N5TS" target="_blank">Math Questionnaire</a></p>
                <a href="javascript:void(0)" onclick='pop_up("/catalog-maker/docs/catalog_survey.html");'><i class="ti-info" style="float: right;color: blue;font-size: 2rem;font-weight: bold;"></i></a>
                <h5><a href="/index.php?view=wiz_wf_desc&bot_id=09b7dc4b78&submit=add">Customize the template</a></h5>
                <h8>For using Math symbols and notations MathJax is included in Survey template. Here is a
                <a href="https://www.physicsoverflow.org/15329/mathjax-basic-tutorial-and-quick-reference" target="_blank">brief tutorial</a>.</h8>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row"  style="padding: 20px;">
     <div class="col-md-6 mb-3 mb-md-0">
        <div class="card py-4 h-100">
            <div class="card-body text-center">
                <h4 class="text-uppercase m-0">Visitor Management</h4>
                <hr class="my-4" />
                <div class="text-black-50">Visitor management requires you to collect visitor information. It is a form of survey where the visitor provides information.
                The receptionist then validates this information once the visitor reaches her desk. With captured information and OTP verification already done, the reception desk stays efficient.
                This quick check is enabled using <a href="/catalog-maker/docs/index.html#scanner">Scanner App</a>.

                <a href="https://1do.in/7,TFBDWVl4SE" target="_blank">Visitor App for Ibeyonde</a></p>
                <a href="javascript:void(0)" onclick='pop_up("/catalog-maker/docs/catalog_visitor.html");'><i class="ti-info" style="float: right;color: blue;font-size: 2rem;font-weight: bold;"></i></a>
                <h5><a href="/index.php?view=wiz_wf_desc&bot_id=6b70e49585&submit=add">Customize the template</a></h5>
            </div>
          </div>
       </div>
    </div>
    <div class="col-md-6 d-md-block d-sm-none d-none mb-3 mb-md-0">
        <div class="card py-4 h-100" style="background-color: black;background-image: url(/catalog-maker/img/office.jpg);background-size: cover;">
          <br/>
          <br/>
        </div>
    </div>
</div>

<!--

<div class="row"  style="padding: 20px;">
    <div class="col-md-12 d-md-block d-sm-none d-none mb-3 mb-md-0">
        <div class="card-body text-center">
            The following catalog pages are generated from the KB (knowledge base). The KB consists of tables that have information about the items.
            You can modify the existing tables for these catalogs to have your item appear in the catalog.
            If you wan to modify the structure of the KB then read the docs and understand how KB is used to render the pages.
        </div>
    </div>
</div>

<div class="row"  style="padding: 20px;">
    <div class="col-md-6 d-md-block d-sm-none d-none mb-3 mb-md-0">
        <div class="card py-4 h-100"   style="background-color: black;background-image: url(/catalog-maker/img/restaurant_flip.jpg);background-size: cover;">
          <br/>
          <br/>
        </div>
     </div>
     <div class="col-md-6 mb-3 mb-md-0">
        <div class="card py-4 h-100">
            <div class="card-body text-center">
                <h4 class="text-uppercase m-0">Menu with 5 item cart</h4>
                <hr class="my-4" />
                <div class="text-black-50"> A perfect catalog app for cafe and ice cream shops delivering to home and office.
                Use this to reach out to customers who want to order online. Check with us to get payment options enabled.

                <a href="https://1do.in/7,aVFXTjRHdn" target="_blank">Willy Wonka Ice-cream</a></p>
                <a href="javascript:void(0)" onclick='pop_up("/catalog-maker/docs/catalog_menu.html");'><i class="ti-info" style="float: right;color: blue;font-size: 2rem;font-weight: bold;"></i></a>
                <h5><a href="/index.php?view=wiz_wf_desc&bot_id=0e39f7f70f&submit=add">Customize the template</a></h5>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row"  style="padding: 20px;">
     <div class="col-md-6 mb-3 mb-md-0">
        <div class="card py-4 h-100">
            <div class="card-body text-center">
                <h4 class="text-uppercase m-0">Menu with 10 item cart</h4>
                <hr class="my-4" />
                <div class="text-black-50">A online restaurant menu lets you order up to 10 items. Perfect for contactless restaurant menu.
                Send it by email or SMS to your customers who want to order from the comfort of home and office. Check with us to get payment options enabled.

                <a href="https://1do.in/7,U3g4UjlPND" target="_blank">Pier 39 Seafood</a></p>
                <a href="javascript:void(0)" onclick='pop_up("/catalog-maker/docs/catalog_menu.html");'><i class="ti-info" style="float: right;color: blue;font-size: 2rem;font-weight: bold;"></i></a>
                <h5><a href="/index.php?view=wiz_wf_desc&bot_id=1f1c2b83f7&submit=add">Customize the template</a></h5>
            </div>
          </div>
       </div>
    </div>
    <div class="col-md-6 d-md-block d-sm-none d-none mb-3 mb-md-0">
        <div class="card py-4 h-100" style="background-color: black;background-image: url(/catalog-maker/img/restaurant.jpg);background-size: cover;">
          <br/>
          <br/>
        </div>
    </div>
</div>
 -->


</div>
<?php
include (__ROOT__ . '/views/_footer.php');
?>
</body>
