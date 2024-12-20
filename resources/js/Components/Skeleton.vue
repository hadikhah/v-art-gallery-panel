
<script setup>
defineProps({
  count: Number,
});
</script>


<template>
  <div class="animate-pulse p-4 space-y-4" v-if="count">
    <div class="h-4 bg-neutral-200 rounded w-3/4"></div>
    <template v-for="index in count" :key="index">
      <div class="h-6 bg-neutral-200 rounded"></div>
    </template>
  </div>
  <div v-else>
    <div class="animate-pulse p-4 space-y-4">
      <div class="h-4 bg-neutral-200 rounded w-3/4"></div>
      <div class="h-6 bg-neutral-200 rounded"></div>
      <div class="h-6 bg-neutral-200 rounded"></div>
    </div>
  </div>
</template>

<style>
</style>
