<script setup lang="ts"></script>

<template>
    <div class="grid grid-cols-12 gap-8 mb-4">
        <div class="col-span-12 lg:col-span-6 xl:col-span-3">
            <div class="mb-0 card">
                <div class="flex justify-between mb-4">
                    <div>
                        <span class="block mb-4 font-medium text-muted-color">Orders</span>
                        <div class="text-xl font-medium text-surface-900 dark:text-surface-0">54 Orders</div>
                    </div>
                    <div class="flex items-center justify-center bg-blue-100 dark:bg-blue-400/10 rounded-border" style="width: 2.5rem; height: 2.5rem">
                        <i class="pi pi-shopping-cart text-blue-500 !text-xl"></i>
                    </div>
                </div>
                <span class="font-medium text-primary">24 new </span>
                <span class="text-muted-color">since last visit</span>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-6 xl:col-span-3">
            <div class="mb-0 card">
                <div class="flex justify-between mb-4">
                    <div>
                        <span class="block mb-4 font-medium text-muted-color">Revenue</span>
                        <div class="text-xl font-medium text-surface-900 dark:text-surface-0">$2.100</div>
                    </div>
                    <div class="flex items-center justify-center bg-orange-100 dark:bg-orange-400/10 rounded-border" style="width: 2.5rem; height: 2.5rem">
                        <i class="pi pi-dollar text-orange-500 !text-xl"></i>
                    </div>
                </div>
                <span class="font-medium text-primary">%52+ </span>
                <span class="text-muted-color">since last week</span>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-6 xl:col-span-3">
            <div class="mb-0 card">
                <div class="flex justify-between mb-4">
                    <div>
                        <span class="block mb-4 font-medium text-muted-color">Customers</span>
                        <div class="text-xl font-medium text-surface-900 dark:text-surface-0">28441</div>
                    </div>
                    <div class="flex items-center justify-center bg-cyan-100 dark:bg-cyan-400/10 rounded-border" style="width: 2.5rem; height: 2.5rem">
                        <i class="pi pi-users text-cyan-500 !text-xl"></i>
                    </div>
                </div>
                <span class="font-medium text-primary">520 </span>
                <span class="text-muted-color">newly registered</span>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-6 xl:col-span-3">
            <div class="mb-0 card">
                <div class="flex justify-between mb-4">
                    <div>
                        <span class="block mb-4 font-medium text-muted-color">Comments</span>
                        <div class="text-xl font-medium text-surface-900 dark:text-surface-0">152 Unread</div>
                    </div>
                    <div class="flex items-center justify-center bg-purple-100 dark:bg-purple-400/10 rounded-border" style="width: 2.5rem; height: 2.5rem">
                        <i class="pi pi-comment text-purple-500 !text-xl"></i>
                    </div>
                </div>
                <span class="font-medium text-primary">85 </span>
                <span class="text-muted-color">responded</span>
            </div>
        </div>
    </div>
    <div className="card"><div class="mb-4 text-xl font-semibold">Bienvenidos al Dashboard</div></div>
</template>
