<?php
if ( ! defined('IN_APP')) exit('No direct script access allowed');
load_view('common/header');
?> 
<form role="form" method="post" action="<?php echo get_url('qrcode/doadd');?>">
  <div class="form-group">
    <label for="number">添加数量</label>
    <input type="number" class="form-control" name="number" min="1" max="10" placeholder="您最多能添加 10 个优惠券">
  </div>
  <div class="form-group">
    <label for="expdays">有效期</label>
    <input type="number" class="form-control" name="expdays"  placeholder="输入有效天数，留空(或 0)表示永久生效">
  </div>
  <div class="form-group">
    <label for="cost">面值</label>
    <input type="number" class="form-control" name="cost"  placeholder="输入兑换券的优惠价格，留空(或 0)表示免费券">
  </div>    
    
  <button type="submit" class="btn btn-default">提交</button>
</form>

<?php
load_view('common/footer');
?>