<center>
  <form autocomplete="off" action="/post.php<?php if (isset($post_tail)) echo $post_tail ?>" method="POST">
    <table>
    <?php
     if (!isset($_GET['id'])):
     echo '
     <tr>
       <th align="right" style="vertical-align:top;"><label for="topic">Topic:</label></th>
       <td><input style="box-shadow: 12px 10px 13px 0px rgba(0, 0, 0, 0.75);" type="text" name="topic" required="true" /></td>
     </tr>';
     endif;
    ?>

    <tr>
      <th align="right" style="vertical-align:top;"><label for="name">Name:</label></th>
      <td><input style="box-shadow: 12px 10px 13px 0px rgba(0, 0, 0, 0.75);"  type="text" name="name" placeholder="Anonymous" /></td>
    </tr>

    <tr>
      <th align="right" style="vertical-align:center;"><label for="email">Email:</label></th>
      <td><input style="box-shadow: 12px 10px 13px 0px rgba(0, 0, 0, 0.75);"  type="text" name="email" />
          <button type="submit" id="last_submit">POST</button></td>
    </tr>

    <tr>
      <th align="right" style="vertical-align:top;"><label for="message">Message:</label></th>
      <td><textarea style="box-shadow: 12px 10px 13px 0px rgba(0, 0, 0, 0.75);" cols="50" rows="10" name="message" required="true"></textarea></td>
    </tr>

    <tr>
     <th align="right" style="vertical-align:top;">Captcha:</th>
     <td style="vertical-align: top;"><label for="captcha"><img style="display: inline;box-shadow: 12px 10px 13px 0px rgba(0, 0, 0, 0.75);" src="captcha.php" alt="CAPTCHA"></img></label></td>
    </tr>

    <tr>
    <th align="right" style="vertical-align:top;">Solve:</th>
    <td><input style="box-shadow: 12px 10px 13px 0px rgba(0, 0, 0, 0.75);" type="text" id="captcha" name="captcha" placeholder="NO SPACES" required="true"/></td>
    </tr>

    <?php if (!isset($_GET['id'])): ?>
    <tr>
      <th align="right" style="vertical-align:center;"><label for="tag">Tag:</label></th>
        <select name="tag" required="true">
    <?php
         if (isset($_GET['tag'])) {
             foreach ($CONF['BBS_TAGS'] as $tag => $_):
                 if ($tag == $_GET['tag'])
                     $default_option = ' selected="true"';
                 else
                     $default_option = '';
             echo "<option value=\"{$tag}\"" . $default_option . ">{$tag}</option>";
             endforeach;
         } else {
             foreach ($CONF['BBS_TAGS'] as $tag => $_):
                 if ($tag == $CONF['DEFAULT_TAG'])
                     $default_option = ' selected="true"';
                 else
                     $default_option = '';
             echo "<option value=\"{$tag}\"" . $default_option . ">{$tag}</option>";
             endforeach;
         }
    ?>
      </select><br/>
    </tr>
    <?php endif; ?>

    <tr style="display:none;">
      <th><label for="pulcinella">Pulcinella:</label></th>
      <td><input name="pulcinella" value="43aa950ba2689dd76e55e2596163a43b" type="hidden" /></td>
    </tr>

    <tr class="required-field">
      <th><label for="category">Category:</label></th>
      <td><input type="text" name="category" /></td>
    </tr>

    <tr class="required-field">
      <th><label for="comment">Comment:</label></th>
      <td><textarea cols="50" rows="10" name="comment"></textarea></td>
    </tr>
        
    </table>
  </form>
</center>
