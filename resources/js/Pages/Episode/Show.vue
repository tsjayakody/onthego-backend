<template>
  <app-layout title="Episode Managment">
    <!-- Header section -->
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Manage Episodes
      </h2>
    </template>

    <!-- card component start -->
    <card>
      <!-- Card header section -->
      <template #header>
        <Link :href="route('episodes.create')">
          <jet-button :type="'button'">Create New</jet-button>
        </Link>
        <div class="w-1/2">
          <jet-input
            id="hosterSearch"
            placeholder="Search..."
            type="text"
            class="mt-1 block w-full"
            v-model="searchQuery"
            autocomplete="hosterSearch"
          />
        </div>
      </template>

      <!-- card content section -->
      <template #content>
        <jet-table :columnNames="this.columnNames">
          <template v-if="episodes.data.length > 0">
            <tr v-for="(episode, key) in episodes.data" :key="key">

              <!-- episode image -->
              <jet-td>
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <img
                      class="h-10 w-10 rounded-sm"
                      :src="episode.episode_image"
                      :alt="episode.name"
                    />
                  </div>
                </div>
              </jet-td>

              <!-- episode name -->
              <jet-td>
                <div class="text-sm text-gray-900">{{ episode.name }}</div>
              </jet-td>

              <!-- show name -->
              <jet-td>
                <div class="text-sm text-gray-900">{{ episode.show.name }}</div>
              </jet-td>

              <!-- is featured -->
              <jet-td>
                <span
                  :class="
                    episode.is_featured
                      ? 'bg-green-100 text-green-800'
                      : 'bg-red-100 text-red-800'
                  "
                  class="
                    px-2
                    inline-flex
                    text-xs
                    leading-5
                    font-semibold
                    rounded-full
                  "
                >
                  {{ episode.is_featured ? "✓" : "✗" }}
                </span>
              </jet-td>

              <!-- is popular -->
              <jet-td>
                <span
                  :class="
                    episode.is_popular
                      ? 'bg-green-100 text-green-800'
                      : 'bg-red-100 text-red-800'
                  "
                  class="
                    px-2
                    inline-flex
                    text-xs
                    leading-5
                    font-semibold
                    rounded-full
                  "
                >
                  {{ episode.is_popular ? "✓" : "✗" }}
                </span>
              </jet-td>

              <!-- episode action buttons -->
              <jet-td class="text-right font-medium">
                <!-- <jet-button class="mr-4">View</jet-button> -->
                <Link :href="route('episodes.edit', { id: episode.id })">
                  <jet-button class="mr-4">Edit</jet-button>
                </Link>
                <jet-button @click="episodeIdBeginDeleted = episode.id">
                  Delete
                </jet-button>
              </jet-td>
            </tr>
          </template>
        </jet-table>
        <pagination
          v-if="episodes.data.length > 0"
          :from="episodes.from"
          :to="episodes.to"
          :total="episodes.total"
          :links="episodes.links"
        ></pagination>
      </template>
    </card>
    <!-- card component end -->
  </app-layout>
      <jet-confirmation-modal
    :show="episodeIdBeginDeleted"
    @close="episodeIdBeginDeleted = null"
  >
    <template #title> Delete Episode </template>

    <template #content>
      Are you sure you would like to delete this episode?
    </template>

    <template #footer>
      <jet-secondary-button @click="episodeIdBeginDeleted = null">
        Cancel
      </jet-secondary-button>

      <jet-danger-button
        class="ml-2"
        @click="deleteEpisode"
        :class="{ 'opacity-25': deleteEpisodeForm.processing }"
        :disabled="deleteEpisodeForm.processing"
      >
        Delete
      </jet-danger-button>
    </template>
  </jet-confirmation-modal>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import JetButton from "@/Jetstream/Button.vue";
import Pagination from "@/Jetstream/Pagination.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import Card from "@/Jetstream/Card.vue";
import JetTable from "@/Jetstream/Table.vue";
import JetTd from "@/Jetstream/Td.vue";
import { Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import JetDangerButton from "@/Jetstream/DangerButton.vue";
import JetConfirmationModal from "@/Jetstream/ConfirmationModal.vue";

export default defineComponent({
  props: ["episodes", "filters"],
  components: {
    AppLayout,
    Card,
    JetInput,
    JetButton,
    Link,
    JetTable,
    JetTd,
    Pagination,
    JetFormSection,
    JetInputError,
    JetLabel,
    JetActionMessage,
    JetSecondaryButton,
    Inertia,
    JetDangerButton,
    JetConfirmationModal,
  },

  data() {
    return {
      searchQuery: "",
      columnNames: ["Image", "Name", "Show", "Featured?", "Popular?", ""],
      episodeIdBeginDeleted: null,
      deleteEpisodeForm: this.$inertia.form(),
    };
  },

  watch: {
    searchQuery(val, oldVal) {
      this.getAllRecords(val);
    },
  },

  methods: {
    getAllRecords: _.debounce((val) => {
      Inertia.get(
        route("episodes.show"),
        { search: val },
        { preserveState: true }
      );
    }, 200),
    deleteEpisode() {
      this.deleteEpisodeForm.delete(
        route("episodes.delete", this.episodeIdBeginDeleted),
        {
          preserveScroll: true,
          preserveState: true,
          onSuccess: () => (this.episodeIdBeginDeleted = null),
        }
      );
    },
  },
});
</script>

