<template>
  <app-layout title="Manage Shows">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Manage Shows
      </h2>
    </template>

    <card>
      <template #header>
        <Link :href="route('shows.create')">
          <jet-button :type="'button'">Create New</jet-button>
        </Link>
        <div class="w-1/2">
          <jet-input
            id="searchInput"
            placeholder="Search..."
            type="text"
            class="mt-1 block w-full"
            v-model="searchQuery"
            autocomplete="search"
          />
        </div>
      </template>

      <template #content>
        <jet-table :columnNames="this.columnNames">
          <template v-if="shows.data.length > 0">
            <tr v-for="(show, key) in shows.data" :key="key">
              <!-- show image -->
              <jet-td>
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <img
                      class="h-10 w-10 rounded-sm"
                      :src="show.show_image"
                      :alt="show.name"
                    />
                  </div>
                </div>
              </jet-td>
              <!-- show name -->
              <jet-td>
                <div class="text-sm text-gray-900">{{ show.name }}</div>
              </jet-td>

              <!-- show slug -->
              <jet-td>
                <div class="text-sm text-gray-900">{{ show.slug }}</div>
              </jet-td>

              <!-- show hoster -->
              <jet-td>
                <div class="text-sm text-gray-900">{{ show.hoster_name }}</div>
              </jet-td>

              <!-- hoster action buttons -->
              <jet-td class="text-right font-medium">
                <!-- <jet-button class="mr-4">View</jet-button> -->
                <Link :href="route('shows.edit', { id: show.id })">
                  <jet-button class="mr-4">Edit</jet-button>
                </Link>
                <jet-button @click="showIdBeginDeleted = show.id">
                  Delete
                </jet-button>
              </jet-td>
            </tr>
          </template>
        </jet-table>
        <pagination
          v-if="shows.data.length > 0"
          :from="shows.from"
          :to="shows.to"
          :total="shows.total"
          :links="shows.links"
        ></pagination>
      </template>
    </card>
  </app-layout>
  <jet-confirmation-modal
    :show="showIdBeginDeleted"
    @close="showIdBeginDeleted = null"
  >
    <template #title> Delete Show </template>

    <template #content>
      Are you sure you would like to delete this show?
    </template>

    <template #footer>
      <jet-secondary-button @click="showIdBeginDeleted = null">
        Cancel
      </jet-secondary-button>

      <jet-danger-button
        class="ml-2"
        @click="deleteShow"
        :class="{ 'opacity-25': deleteShowForm.processing }"
        :disabled="deleteShowForm.processing"
      >
        Delete
      </jet-danger-button>
    </template>
  </jet-confirmation-modal>
</template>

<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import Card from "@/Jetstream/Card.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetTable from "@/Jetstream/Table.vue";
import JetTd from "@/Jetstream/Td.vue";
import Pagination from "@/Jetstream/Pagination.vue";
import JetDangerButton from "@/Jetstream/DangerButton.vue";
import JetConfirmationModal from "@/Jetstream/ConfirmationModal.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import { Inertia } from "@inertiajs/inertia";

export default defineComponent({
  components: {
    AppLayout,
    Card,
    JetInput,
    JetButton,
    Link,
    JetTable,
    JetTd,
    Pagination,
    JetConfirmationModal,
    JetDangerButton,
    JetActionMessage,
    JetSecondaryButton,
    Inertia,
  },

  props: ["shows", "filters"],
  watch: {
    searchQuery(val, oldVal) {
      this.getAllRecords(val);
    },
  },
  data() {
    return {
      searchQuery: "",
      columnNames: ["Image", "Name", "Slug", "hoster", ""],
      showIdBeginDeleted: null,
      deleteShowForm: this.$inertia.form(),
    };
  },
  methods: {
    getAllRecords: _.debounce((val) => {
      Inertia.get(
        route("shows.show"),
        { search: val },
        { preserveState: true }
      );
    }, 200),
    deleteShow() {
      this.deleteShowForm.delete(
        route("shows.delete", this.showIdBeginDeleted),
        {
          preserveScroll: true,
          preserveState: true,
          onSuccess: () => (this.showIdBeginDeleted = null),
        }
      );
    },
  },
});
</script>

