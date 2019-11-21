<form id="add-task" action="/tasks/add" method="post">
	<input name="id" type="hidden" value="<?php if (isset($task)) { echo $task->get('id'); } ?>">
	<input name="parent_id" type="hidden" value="<?php if (isset($task)) { echo $task->get('parent'); } ?>">
	<div class="input-group form-group date">
		<input type="text" class="form-control" placeholder="date" name="date" value="<?php if (isset($task)) { echo $task->get('date'); } ?>">
	</div>
	<div class="input-group form-group">
		<input type="text" class="form-control" placeholder="Header" name="heading" value="<?php if (isset($task)) { echo $task->get('heading'); } ?>">
	</div>
	<div class="form-group">
		<select name="status" class="form-control">
			<option value="0" <?php if (isset($task) && $task->get('status') == 0) { echo 'selected="selected"'; } ?>>In progress</option>
			<option value="1" <?php if (isset($task) && $task->get('status') == 1) { echo 'selected="selected"'; } ?>>Ready</option>
		</select>
	</div>
	<div class="input-group form-group">
		<textarea class="form-control" name="content"><?php if (isset($task)) { echo $task->get('content'); } ?></textarea>
	</div>
    <button class="save-task" type="button">Save</button>
</form>
