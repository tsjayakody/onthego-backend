<template>
  <div
    class="
      flex
      h-14
      w-full
      rounded-md
      overflow-hidden
      border-gray-600 border-1
      shadow
      cursor-pointer
      hover:bg-gray-300
      transition-colors
    "
    v-if="show !== null"
  >
    <span
      class="block w-14 h-14 rounded-sm bg-cover bg-no-repeat bg-center"
      :style="'background-image: url(\'' + show?.show_image + '\');'"
    >
    </span>
    <div class="p-2">
      <p class="text-sm flex-wrap">{{ show?.name }}</p>
      <p class="text-xs text-gray-600">{{ show?.hoster.name }}</p>
    </div>
  </div>
  <div
    class="
      h-14
      w-full
      rounded-md
      overflow-hidden
      border-gray-600 border-1
      shadow
      cursor-pointer
      hover:bg-gray-300
      transition-colors
      text-center
    "
    v-else
  >
    <div class="p-2">
      <p class="text-sm flex-wrap">No Selected Show</p>
      <p class="text-xs text-gray-600">Click on this to select one.</p>
    </div>
  </div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
  props: {
    showId: {
      type: Number,
      default: null,
    },
  },
  watch: {
    showId() {
        this.getCurrentShow();
    },
  },
  data() {
    return {
      show: null,
    };
  },
  methods: {
    async getCurrentShow() {
      try {
        const response = await axios.get(
          "/api/shows/" + this.showId
        );
        this.show = response.data[0];
      } catch (error) {
        this.show = null;
        console.log(error);
      }
    },
  },
});
</script>

