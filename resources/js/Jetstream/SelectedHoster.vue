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
    v-if="hoster !== null"
  >
    <span
      class="block w-14 h-14 rounded-sm bg-cover bg-no-repeat bg-center"
      :style="'background-image: url(\'' + hoster?.hoster_image + '\');'"
    >
    </span>
    <div class="p-2">
      <p class="text-sm flex-wrap">{{ hoster?.name }}</p>
      <p class="text-xs text-gray-600">{{ hoster?.designation }}</p>
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
      <p class="text-sm flex-wrap">No Selected Hoster</p>
      <p class="text-xs text-gray-600">Click on this to select one.</p>
    </div>
  </div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
  props: {
    hosterId: {
      type: Number,
      default: null,
    },
  },
  watch: {
    hosterId() {
        this.getCurrentHoster();
    },
  },
  data() {
    return {
      hoster: null,
    };
  },
  methods: {
    async getCurrentHoster() {
      try {
        const response = await axios.get(
          "/api/hosters/" + this.hosterId
        );
        this.hoster = response.data;
      } catch (error) {
        this.hoster = null;
        console.log(error);
      }
    },
  },
});
</script>

