<?php if (!defined('ABSPATH')) exit;?>
<h3 style="color:#000;">General</h3>
<table class="form-table">
  <tr>
    <th>Header Image URL</th>
    <td><?php $controls->text_url('theme_header_image', 30); ?></td>
  </tr>
  <tr>
    <th>Background Colour</th>
    <td>
      <?php $controls->color('theme_background_colour'); ?>
      <p class="description" style="display: inline">Hex values, e.g. #333333</p>
    </td>
  </tr>
  <tr>
    <th>Accent Colour</th>
    <td>
      <?php $controls->color('theme_accent_colour'); ?>
      <p class="description" style="display: inline">Hex values, e.g. #222222</p>
    </td>
  </tr>
  <tr>
    <th>Accent Text Colour</th>
    <td>
      <?php $controls->color('theme_accent_text_colour'); ?>
      <p class="description" style="display: inline">Hex values, e.g. #FFFFFF</p>
    </td>
  </tr>
</table>

<h3 style="color:#000;">Content</h3>
<table class="form-table">
    <tr>
      <th>Movies</th>
      <td>
        <?php $controls->checkbox('theme_include_movies', 'Show movie listings'); ?><br>
        <div style="margin-left: 23px; margin-bottom: 10px;">
          <?php $controls->checkbox('theme_include_scheduled', 'Include scheduled listings'); ?><br>
          <?php $controls->checkbox('theme_include_popups', 'Include popup listings'); ?><br>
          <?php $controls->checkbox('theme_include_widget', 'Include widget listings'); ?><br>
        </div>
        <label><?php $controls->radio('theme_display_type', 'nowplaying'); ?>Now Playing</label>
        <label><?php $controls->radio('theme_display_type', 'comingsoon'); ?>Coming Soon</label>
      </td>
    </tr>
    <tr>
      <th>Coming Soon URL</th>
      <td>
        <?php $controls->text_url('theme_comingsoon_url', 30); ?>
        <p class="description" style="display: inline">(without trailing /)</p>
      </td>
    </tr>
</table>

<h3 style="color:#000;">Links</h3>
<table class="form-table">
  <tr>
    <th>Disable Links</th>
    <td><?php $controls->checkbox('theme_disable_links', ''); ?></td>
  </tr>
  <tr>
    <th>Website Link Title</th>
    <td><?php $controls->text('theme_website_title', 30); ?></td>
  </tr>
  <tr>
    <th>Website Link URL</th>
    <td><?php $controls->text_url('theme_website_url', 30); ?></td>
  </tr>
  <tr>
    <th>Facebook Link Title</th>
    <td><?php $controls->text('theme_facebook_title', 30); ?></td>
  </tr>
  <tr>
    <th>Facebook Link URL</th>
    <td><?php $controls->text_url('theme_facebook_url', 30); ?></td>
  </tr>
</table>

<h3 style="color:#000;">Footer</h3>
<table class="form-table">
  <tr>
    <th>Company Name</th>
    <td><?php $controls->text('theme_footer_name', 30); ?></td>
  </tr>
  <tr>
    <th>Company Address</th>
    <td><?php $controls->text('theme_footer_address', 30); ?></td>
  </tr>
</table>
