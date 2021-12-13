<template>
  <app-layout title="Manage Hosters">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Manage Hosters
      </h2>
    </template>

    <card>
      <template #header>
        <Link :href="route('hosters.create')">
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

      <template #content>
        <jet-table :columnNames="this.columnNames">
          <template v-if="hosters.data.length > 0">
            <tr v-for="(hoster, key) in hosters.data" :key="key">
              <!-- hoster image -->
              <jet-td>
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <img
                      class="h-10 w-10 rounded-sm"
                      :src="hoster.hoster_image"
                      :alt="hoster.name"
                    />
                  </div>
                </div>
              </jet-td>

              <!-- hoster name -->
              <jet-td>
                <div class="text-sm text-gray-900">{{ hoster.name }}</div>
              </jet-td>

              <!-- hoster slug -->
              <jet-td>
                <div class="text-sm text-gray-900">{{ hoster.slug }}</div>
              </jet-td>

              <!-- hoster status -->
              <jet-td>
                <span
                  :class="
                    hoster.is_approved
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
                  {{ hoster.is_approved ? "✓" : "✗" }}
                </span>
              </jet-td>

              <!-- hoster action buttons -->
              <jet-td class="text-right font-medium">
                <!-- <jet-button class="mr-4">View</jet-button> -->
                <Link :href="route('hosters.edit', { id: hoster.id })">
                  <jet-button class="mr-4">Edit</jet-button>
                </Link>
                <jet-button @click="hosterIdBeginDeleted = hoster.id">
                  Delete
                </jet-button>
              </jet-td>
            </tr>
          </template>
        </jet-table>
        <pagination
          v-if="hosters.data.length > 0"
          :from="hosters.from"
          :to="hosters.to"
          :total="hosters.total"
          :links="hosters.links"
        ></pagination>
      </template>
    </card>
  </app-layout>
    <jet-confirmation-modal
    :show="hosterIdBeginDeleted"
    @close="hosterIdBeginDeleted = null"
  >
    <template #title> Delete Hoster </template>

    <template #content>
      Are you sure you would like to delete this hoster?
    </template>

    <template #footer>
      <jet-secondary-button @click="hosterIdBeginDeleted = null">
        Cancel
      </jet-secondary-button>

      <jet-danger-button
        class="ml-2"
        @click="deleteHoster"
        :class="{ 'opacity-25': deleteHosterForm.processing }"
        :disabled="deleteHosterForm.processing"
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
  components: {
    Pagination,
    JetTable,
    JetTd,
    Card,
    AppLayout,
    JetActionMessage,
    JetButton,
    JetFormSection,
    JetInput,
    JetInputError,
    JetLabel,
    JetSecondaryButton,
    Link,
    JetConfirmationModal,
    JetDangerButton,
    Inertia,
  },
  props: ["hosters", "filters"],
  data() {
    return {
      searchQuery: this.filters.search,
      hosterIdBeginDeleted: null,
      deleteHosterForm: this.$inertia.form(),
      columnNames: [
        "Image",
        "Name",
        "Slug",
        "Status",
        "",
      ],
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
        route("hosters.show"),
        { search: val },
        { preserveState: true }
      );
    }, 200),
    deleteHoster() {
      this.deleteHosterForm.delete(
        route("hosters.delete", this.hosterIdBeginDeleted),
        {
          preserveScroll: true,
          preserveState: true,
          onSuccess: () => (this.hosterIdBeginDeleted = null),
        }
      );
    },
  },
});
</script>
