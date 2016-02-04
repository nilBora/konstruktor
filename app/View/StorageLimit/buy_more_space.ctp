<div class="container">
    <div class="row row-centered">
        <div class="col-md-6 col-centered">
            <h1>
                <?echo __('Buy More Space');?>
            </h1>
            <select class="form-select">
                <?php
                for($i = 0; $i < 10; $i++) {?>
                    <option value="<?php echo $i;?>"><?php echo ($i+1)*100 . " MB";?></option>
                    <?php
                }
                ?>
            </select>
            <div style="margin: 15px 0">
                <a href="javascript(void:0)" id="buy-more-btn" class="btn btn-danger"> <? echo __('Buy') ?> </a>
            </div>
            <div id="result-notify" style="margin: 15px 0; color: #5cb85c; font-weight: bold; display: none">
                Space was successfully added !
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $('#buy-more-btn').click(function(e) {
            e.preventDefault();
            var selected = $('.form-select').val();
            var buy_storage = (parseInt(selected) + 1)*100 * (Math.pow(1024,2));
            $.ajax({
                url: '/storagelimit/buymorespace',
                type: 'POST',
                data: {buy_storage: buy_storage},
                dataType: "json",
                success: function (data) {
                    if(data && data.hasOwnProperty('success') && data.success) {
                        $('#result-notify').fadeIn('slow', function(){
                            setTimeout(function(){ $('#result-notify').fadeOut('slow')}, 3000);
                        })
                    }
                }
            });
        });
    })
</script>