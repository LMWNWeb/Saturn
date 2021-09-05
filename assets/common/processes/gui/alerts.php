<?php

function alert($type, $message): bool
{
    $message = checkOutput('DEFAULT', $message);
    $message = stripslashes($message);

    if ($type == 'ERROR') {
        echo '<br>
                            <div class="duration-300 transform bg-red-100 border-l-4 border-red-500 hover:-translate-y-2">
                                <div class="p-5 border border-l-0 rounded-r shadow-sm">
                                    <h6 class="mb-2 font-semibold leading-5">[ERROR] '.$message.'</h6>
                                </div>
                            </div>';
    } elseif ($type == 'WARNING') {
        echo '<br><div class="duration-300 transform bg-yellow-100 border-l-4 border-yellow-500 hover:-translate-y-2">
                                <div class="p-5 border border-l-0 rounded-r shadow-sm">
                                    <h6 class="mb-2 font-semibold leading-5">[WARNING] '.$message.'</h6>
                                </div>
                            </div>';
    } elseif ($type == 'SUCCESS') {
        echo '<br><div class="duration-300 transform bg-green-100 border-l-4 border-green-500 hover:-translate-y-2">
                                <div class="p-5 border border-l-0 rounded-r shadow-sm">
                                    <h6 class="mb-2 font-semibold leading-5">'.$message.'</h6>
                                </div>
                            </div>';
    } elseif ($type == 'INFO') {
        echo '<br><div class="duration-300 transform bg-blue-100 border-l-4 border-blue-500 hover:-translate-y-2">
                                <div class="p-5 border border-l-0 rounded-r shadow-sm">
                                    <h6 class="mb-2 font-semibold leading-5">'.$message.'</h6>
                                </div>
                            </div>';
    } else {
        echo '<br><div class="duration-300 transform bg-gray-100 border-l-4 border-gray-500 hover:-translate-y-2">
                                <div class="p-5 border border-l-0 rounded-r shadow-sm">
                                    <h6 class="mb-2 font-semibold leading-5">'.$message.'</h6>
                                </div>
                            </div>';
    }

    return true;
}

function static_alert($type, $message): bool
{
    $message = checkOutput('DEFAULT', $message);
    $message = stripslashes($message);

    if ($type == 'ERROR') {
        echo '<div class="bg-red-100 border-l-4 border-red-500">
                                <div class="p-5 border border-l-0 rounded-r shadow-sm">
                                    <h6 class="mb-2 font-semibold leading-5">[ERROR] '.$message.'</h6>
                                </div>
                            </div>';
    } elseif ($type == 'WARNING') {
        echo '<div class="bg-yellow-100 border-l-4 border-yellow-500">
                                <div class="p-5 border border-l-0 rounded-r shadow-sm">
                                    <h6 class="mb-2 font-semibold leading-5">[WARNING] '.$message.'</h6>
                                </div>
                            </div>';
    } elseif ($type == 'SUCCESS') {
        echo '<div class="bg-green-100 border-l-4 border-green-500">
                                <div class="p-5 border border-l-0 rounded-r shadow-sm">
                                    <h6 class="mb-2 font-semibold leading-5">'.$message.'</h6>
                                </div>
                            </div>';
    } elseif ($type == 'INFO') {
        echo '<div class="bg-blue-100 border-l-4 border-blue-500">
                                <div class="p-5 border border-l-0 rounded-r shadow-sm">
                                    <h6 class="mb-2 font-semibold leading-5">'.$message.'</h6>
                                </div>
                            </div>';
    } else {
        echo '<div class="bg-gray-100 border-l-4 border-gray-500">
                                <div class="p-5 border border-l-0 rounded-r shadow-sm">
                                    <h6 class="mb-2 font-semibold leading-5">'.$message.'</h6>
                                </div>
                            </div>';
    }

    return true;
}
