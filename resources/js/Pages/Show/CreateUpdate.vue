<template>
  <app-layout title="Manage Shows">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Manage Shows
      </h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <jet-form-section @submitted="createUpdateShow">
          <template #title>
            {{ show == undefined ? "Create new show" : "Edit show" }}
          </template>

          <template #description>
            <ul>
              <li>• Create or Update the show.</li>
              <li>• Image accept ratio should be 1:1.</li>
              <li>• Image formt should be .png, .jpg or .jpeg</li>
            </ul>
          </template>

          <template #form>
            <!-- Name -->
            <div class="col-span-6 sm:col-span-4">
              <jet-label for="name" value="Name" />
              <jet-input
                id="name"
                type="text"
                class="mt-1 block w-full"
                v-model="form.name"
                autocomplete="name"
              />
              <jet-input-error :message="form.errors.name" class="mt-2" />
            </div>

            <!-- Slug -->
            <div class="col-span-6 sm:col-span-4" v-if="show">
              <jet-label for="slug" value="Slug" />
              <jet-input
                id="slug"
                type="text"
                class="mt-1 block w-full"
                :value="show.slug"
                autocomplete="slug"
                disabled
              />
            </div>

            <!-- hoster -->

            <div class="col-span-6 sm:col-span-4">
              <jet-label for="hoster" value="Hoster" />
              <selected-hoster
                :hosterId="form.hoster_id"
                @click="isOpenHosterSelector = true"
              ></selected-hoster>
              <jet-input-error :message="form.errors.hoster_id" class="mt-2" />
            </div>

            <!-- category cover image -->
            <div class="col-span-6 sm:col-span-4">
              <!-- Profile Photo File Input -->
              <input
                type="file"
                class="hidden"
                ref="photo"
                @change="updatePhotoPreview"
              />

              <jet-label for="photo" value="Cover Image" />

              <!-- Current Profile Photo -->
              <div class="mt-2" v-show="!photoPreview">
                <img
                  :src="show ? show.show_image : '/noimage.png'"
                  :alt="form.name"
                  class="rounded-sm object-cover"
                  style="width: 150px; height: 150px"
                />
              </div>

              <!-- New Profile Photo Preview -->
              <div class="mt-2" v-show="photoPreview">
                <span
                  class="block rounded-sm bg-cover bg-no-repeat bg-center"
                  style="width: 150px; height: 150px"
                  :style="'background-image: url(\'' + photoPreview + '\');'"
                >
                </span>
              </div>

              <jet-secondary-button
                class="mt-2 mr-2"
                type="button"
                @click.prevent="selectNewPhoto"
              >
                Select A Photo
              </jet-secondary-button>

              <jet-input-error :message="form.errors.photo" class="mt-2" />
            </div>

            <!-- description -->
            <div class="col-span-6 sm:col-span-4">
              <jet-label for="description" value="Description" />
              <jet-text-area
                id="description"
                type="text"
                class="mt-1 block w-full"
                v-model="form.description"
                autocomplete="description"
                cols="5"
                rows="10"
              />

              <jet-input-error
                :message="form.errors.description"
                class="mt-2"
              />
            </div>
          </template>

          <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
              Saved.
            </jet-action-message>

            <jet-button
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
            >
              Save
            </jet-button>

            <Link :href="route('shows.show')" class="ml-4">
              <jet-secondary-button :type="'button'"
                >Cancel</jet-secondary-button
              >
            </Link>
          </template>
        </jet-form-section>
      </div>
    </div>
  </app-layout>
  <hoster-select-modal
    :show="isOpenHosterSelector"
    @update:hoster-changed="onHosterChanged"
    @close="isOpenHosterSelector = false"
  ></hoster-select-modal>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetTextArea from "@/Jetstream/InputArea.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import HosterSelectModal from "@/jetstream/HosterSelect.vue";
import { Link } from "@inertiajs/inertia-vue3";
import SelectedHoster from "@/Jetstream/SelectedHoster.vue";

export default defineComponent({
  components: {
    AppLayout,
    JetActionMessage,
    JetButton,
    JetFormSection,
    JetInput,
    JetInputError,
    JetLabel,
    JetSecondaryButton,
    Link,
    JetTextArea,
    SelectedHoster,
    HosterSelectModal,
  },
  props: {
    show: {
      type: Object,
      default: undefined,
    },
    hosters: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      form: this.$inertia.form({
        name: "",
        description: "",
        hoster_id: null,
        photo: null,
      }),
      photoPreview: null,
      isOpenHosterSelector: false,
    };
  },
  mounted() {
    if (this.show) {
      this.form.name = this.show.name;
      this.form.description = this.show.description;
      this.form.hoster_id = this.show.hoster_id;
    }
  },
  methods: {
    createUpdateShow() {
      if (this.show === undefined) {
        if (this.$refs.photo) {
          this.form.photo = this.$refs.photo.files[0];
          this.form.post(route("shows.store"), {
            errorBag: "createUpdateShow",
            preserveScroll: true,
          });
        }
      } else {
        if (this.$refs.photo) {
          this.form.photo = this.$refs.photo.files[0];
        } else {
          delete this.form.photo;
        }

        this.form.post(route("shows.update", { id: this.show.id }), {
          errorBag: "createUpdateShow",
          preserveScroll: true,
        });
      }
    },
    selectNewPhoto() {
      this.$refs.photo.click();
    },

    updatePhotoPreview() {
      const photo = this.$refs.photo.files[0];

      if (!photo) return;

      const reader = new FileReader();

      reader.onload = (e) => {
        this.photoPreview = e.target.result;
      };

      reader.readAsDataURL(photo);
    },
    clearPhotoFileInput() {
      if (this.$refs.photo?.value) {
        this.$refs.photo.value = null;
      }
    },
    onHosterChanged(id) {
      this.form.hoster_id = id;
    },
  },
});
</script>
