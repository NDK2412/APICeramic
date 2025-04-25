<!-- resources/views/admin/llm_settings.blade.php -->
<div class="mt-6">
    <h2 class="text-lg font-semibold mb-4">Cài đặt mô hình hệ thống</h2>
    <?php if(session('llm_success')): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <?php echo e(session('llm_success')); ?>

        </div>
    <?php endif; ?>
    <?php if(session('llm_error')): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <?php echo e(session('llm_error')); ?>

        </div>
    <?php endif; ?>
    <form action="<?php echo e(route('admin.updateLLMSettings')); ?>" method="POST" class="space-y-4">
        <?php echo csrf_field(); ?>
        <div>
            <label for="llm_model" class="block text-sm font-medium text-gray-700">Chọn mô hình LLM</label>
            <select name="llm_model" id="llm_model" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <?php $__currentLoopData = $availableModels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($model); ?>" <?php echo e(($llmModel && $llmModel->value === $model) ? 'selected' : ''); ?>><?php echo e($model); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['llm_model'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div>
            <label for="llm_api_key" class="block text-sm font-medium text-gray-700">API Key</label>
            <input type="text" name="llm_api_key" id="llm_api_key" value="<?php echo e($llmApiKey->value ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Nhập API Key">
            <?php $__errorArgs = ['llm_api_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div>
            <button type="submit" class="action-btn save-btn">
            <i class="fas fa-download"></i>Lưu cài đặt
            </button>
        </div>
    </form>
</div><?php /**PATH D:\Xampp\htdocs\Ceramic_Detection\Ceramic_Detection\resources\views/llm_settings.blade.php ENDPATH**/ ?>