
  <div class="row sel3">
    <?php
        include_once(__ROOT__ . '/classes/sms/SmsMinify.php');
        $min = new SmsMinify();
        $url_otp = $min->createMicroAppUrlOtp($user_id, $bot_id);

        $html_otp = urlencode("https://".$url_otp);
        $url = $min->createMicroAppUrl($user_id, $bot_id);

        $html = urlencode("https://".$url);
        ?>

        <div class="col-lg-3 col-md-3">
            <form action="/index.php"  method="get">
            <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
            <input type=hidden name=view value="<?php echo WIZ_WF_PAGES; ?>">
            <button type="submit" name="submit" value="microapp"  style="background: transparent; border: 0;"><h3>Back</h3></button>
            </form>
        </div>

        <div class="col-lg-3 col-md-3">
            <a  style="background: transparent; border: 0;"
                     data-toggle="collapse"
                     data-target="#collapseGraph"><h3>Sub-Graph<?php echo $Icons->get("bezier", 1, "green"); ?></h3></a>
        </div>
        <div class="col-lg-3 col-md-3">
            <a href="http://<?php echo $url; ?>"  style="background: transparent; border: 0;" target="_blank"><h3>Open Catalog</h3></a>
        </div>

        <div class="col-lg-3 col-md-3">
            <form class="form-inline" action="/index.php"  method="get" style="float: left;">
            <select  class="form-control" name="css"  style="height: 1.5rem; padding: 1px;margin: 1px;">
                   <option value="light" <?php echo ($wf['css']=="light" ? "selected" : ""); ?>>light</option>
                   <option value="black" <?php echo ($wf['css']=="black" ? "selected" : ""); ?>>black</option>
                   <option value="white" <?php echo ($wf['css']=="white" ? "selected" : ""); ?>>white</option>
                   <option value="blue" <?php echo ($wf['css']=="blue" ? "selected" : ""); ?>>blue</option>
               </select>
            <input type=hidden name=view value="workflow_node">
            <input type=hidden name=bot_id value="<?php echo $bot_id; ?>">
            <input type="hidden" name="state" value="<?php echo $state; ?>">
            <button  type="submit" name="submit" value="set_css"   style="background: transparent; border: 0;"><h3>Select Skin</h3></button>
            </form>
        </div>

        <div class="col-lg-12 col-md-12">
             <div class="panel-group" id="accordion">
                <!-- First Panel -->
                <div class="panel panel-default">
                    <div id="collapseGraph" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="row">
                                <?php include(__ROOT__.'/views/sms/wiz/wiz_wf_node_graph.php'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   </div>
