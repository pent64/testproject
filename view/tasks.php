<div class="container" style="height:auto; background: #fff;">
	<div class="row">
		<div class="col-sm-12">
			<button class="btn add-task">Add task</button>
			<div class="add-task" style="display: none;">
			</div>
		</div>
    </div>
    <div class="row">
		<div class="col-sm-12 tasks">
			<?php
            if (isset($tasks)) {
				foreach ($tasks as $task) {
					?>
					<div class="row task <?php if ($task->get('tagged')) { ?>tagged<?php } ?> <?php if ($task->get('status')) { ?>finished<?php } ?>" data-id="<?php echo $task->get('id'); ?>">
						<div class="col-sm-3 heading"><?php echo $task->get('heading'); ?></div>
						<div class="col-sm-3 date"><?php echo $task->get('date'); ?></div>
						<div class="col-sm-3 content"><?php echo $task->get('content'); ?></div>
						<div class="col-sm-3 buttons">
							<button class="btn edit-task">Edit</button>
                            <button class="btn tag-task"><?php if ($task->get('tagged')) { ?>Untag<?php } else { ?>Tag<?php } ?></button>
                            <button class="btn delete-task">Delete</button>
							<button class="btn add-subtask">Add Subtask</button>
						</div>
                        <div class="form"></div>
						<?php
						if (!empty($task->getChildren())) {
							?>
							<div class="children container">
							<?php foreach ($task->getChildren() as $child) {
								?>
								<div class="row task <?php if ($child->get('tagged')) { ?>tagged<?php } ?> <?php if ($child->get('status')) { ?>finished<?php } ?>" data-id="<?php echo $child->get('id'); ?>">
									<div class="col-sm-3 heading"><?php echo $child->get('heading'); ?></div>
									<div class="col-sm-3 date"><?php echo $child->get('date'); ?></div>
									<div class="col-sm-3 content"><?php echo $child->get('content'); ?></div>
									<div class="col-sm-3 buttons">
										<button class="btn edit-task">Edit</button>
                                        <button class="btn tag-task"><?php if ($child->get('tagged')) { ?>Untag<?php } else { ?>Tag<?php } ?></button>
                                        <button class="btn delete-task">Delete</button>
									</div>
                                    <div class="form"></div>
								</div>
								<?php
							}
							?>
							</div>
							<?php
						} ?>
					</div>
				<?php
				}
			} ?>
		</div>
	</div>
</div>