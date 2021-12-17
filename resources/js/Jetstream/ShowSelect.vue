<template>
  <modal
    :show="show"
    :max-width="maxWidth"
    :closeable="closeable"
    @close="close"
  >
    <div class="px-6 py-4">
      <div class="text-lg">Select Show</div>

      <div class="mt-4">
        <jet-input
          id="search-text"
          type="text"
          class="mt-1 block w-full"
          v-model="term"
          placeholder="Search for show....."
          autocomplete="search"
        />
      </div>

      <div class="h-96 overflow-y-auto">
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
            mt-4
          "
          v-for="(show, key) in shows"
          :key="key"
          v-on:click="selectShow(show.id)"
        >
          <span
            class="block w-14 h-14 rounded-sm bg-cover bg-no-repeat bg-center"
            :style="'background-image: url(\'' + show.show_image + '\');'"
          >
          </span>
          <div class="p-2">
            <p class="text-sm flex-wrap">{{ show.name }}</p>
            <p class="text-xs text-gray-600">{{ show.hoster.name }}</p>
          </div>
        </div>
      </div>
    </div>

    <div class="px-6 py-4 bg-gray-100 text-right">
      <jet-secondary-button @click="close"> Cancel </jet-secondary-button>
    </div>
  </modal>
</template>

<script>
import { defineComponent } from "vue";
import Modal from "@/Jetstream/Modal.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import { throttle } from "lodash";
import axios from "axios";

export default defineComponent({
  emits: ["close", "update:show-changed"],

  data() {
    return {
      term: "",
      shows: [],
    };
  },

  components: {
    Modal,
    JetInput,
    JetSecondaryButton,
  },

  props: {
    show: {
      default: false,
    },
    maxWidth: {
      default: "2xl",
    },
    closeable: {
      default: true,
    },
  },

  watch: {
    term(val) {
      if (val.length > 2) {
        this.filterShows(val);
      } else {
        this.shows = [];
      }
    },
  },

  methods: {
    close() {
      this.term = "";
      this.$emit("close");
    },
    filterShows: throttle(function (val) {
      this.getShows(val);
    }),
    async getShows(term) {
      try {
        const res = await axios.get("/api/shows/", { params: { term } });
        this.shows = res.data;
      } catch (error) {
        console.log(error);
        this.shows = [];
      }
    },
    selectShow(id) {
      this.$emit("update:show-changed", id);
      this.term = "";
      this.close();
    },
  },
});
</script>
