<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
<div class="notice notice-success is-dismissible">
	<p>Saved successfully</p>
</div>
<?php elseif (isset($_GET['error']) && $_GET['error'] == 1): ?>
<div class="notice notice-error is-dismissible">
	<p>Saved not successfully</p>
</div>
<?php endif; ?>

<div class="wrap">
	<?php screen_icon(); ?>
	<h1>
		TK Slideshow 
		<a href="<?php echo admin_url( 'themes.php?page=tk-slideshow-new' ); ?>" class="page-title-action">Add New</a>
	</h1>
	<div class="counter-meta">
		<img src="<?php echo plugins_url() .  '/tkslideshow/images/cus-icon.png' ; ?>" alt="icon" /> 
	</div>

	<div class="tablenav top">
		<div class="tablenav-pages one-page">
			<div class="displaying-num"><?php echo sizeof($slideshows) . ' item' ?></div>
		</div>
	</div>
	
	<!-- using admin_action_ . $_REQUEST['action'] hook in admin.php -->
	<table class="wp-list-table widefat fixed striped posts">
		<thead>
			<tr>
				<td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox"></td>
				<th>Name</th>
				<th>Description</th>
				<th>Link URL</th>
				<th>Link Image</th>
				<th>Created</th>
				<th width="40">Active</th>
				<th width="40">Order</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody id="the-list">
		<?php if (isset($slideshows)): ?>
			<?php foreach($slideshows as $slide): ?>
			<tr>
				<th></th>
				<th><?php echo $slide->name ?></th>
				<td><?php echo addslashes($slide->description) ?></td>
				<td><?php echo $slide->link_url ?></td>
				<td><img src="<?php echo $slide->link_image ?>" width="100" /></td>
				<td><?php echo $slide->created_date ?></td>
				<td>
					<?php if ($slide->is_active): ?>
						<a href="<?php echo admin_url( 'themes.php?page=tk-slideshow&action=inactive&id='. $slide->id ); ?>">
							<img src="<?php echo esc_url( admin_url( 'images/yes.png' ) ); ?>" alt="" />
						</a>
					<?php else: ?>
						<a href="<?php echo admin_url( 'themes.php?page=tk-slideshow&action=active&id='. $slide->id ); ?>">
							<img src="<?php echo esc_url( admin_url( 'images/no.png' ) ); ?>" alt="" />
						</a>
					<?php endif; ?>
				</td>
				<td><?php echo $slide->ordering ?></td>
				<td>
					<a href="<?php echo admin_url( 'themes.php?page=tk-slideshow&action=delete&id='. $slide->id ); ?>" onclick="return confirm('Want to delete?');">Delete</a> | 
					<a href="<?php echo admin_url( 'themes.php?page=tk-slideshow-edit&id='. $slide->id ); ?>">Edit</a>
				</td>
			</tr>
			<?php endforeach; ?>
		<?php endif; ?>
		</tbody>
	</table>
		<!--
		<p class="submit">
			<input id="save" class="button button-primary" type="submit" value="Save" name="save" />
			<input id="cancel" class="button" type="submit" value="Cancel" name="cancel" />
		</p>
		-->

</div> <!-- end div.wrap -->