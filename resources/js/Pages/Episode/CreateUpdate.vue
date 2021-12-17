<template>
  <app-layout title="Manage Episodes">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Manage Episodes
      </h2>
    </template>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
      <jet-form-section @submitted="createUpdateEpisode">
        <template #title>
          {{ episode == undefined ? "Create new episode" : "Edit episode" }}
        </template>
        <template #description>
          <ul>
            <li>• Create or Update the episode.</li>
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
              placeholder="Episode Name"
            />
            <jet-input-error :message="form.errors.name" class="mt-2" />
          </div>

          <!-- Slug -->
          <div class="col-span-6 sm:col-span-4" v-if="episode">
            <jet-label for="slug" value="Slug" />
            <jet-input
              id="slug"
              type="text"
              class="mt-1 block w-full"
              :value="episode.slug"
              autocomplete="slug"
              disabled
            />
          </div>

          <!-- episode cover image -->
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
                :src="episode ? episode.episode_image : '/noimage.png'"
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

          <!-- show -->
          <div class="col-span-6 sm:col-span-4">
            <jet-label for="show" value="Show" />
            <selected-show
              :showId="form.show_id"
              @click="isOpenShowSelector = true"
            ></selected-show>
            <jet-input-error :message="form.errors.show_id" class="mt-2" />
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
              placeholder="Episode Description"
            />

            <jet-input-error :message="form.errors.description" class="mt-2" />
          </div>

          <!-- Media URL -->
          <div class="col-span-6 sm:col-span-4">
            <jet-label for="media_url" value="Media URL" />
            <jet-input
              id="name"
              type="url"
              class="mt-1 block w-full"
              v-model="form.media_url"
              autocomplete="media_url"
              placeholder="Audio URL"
            />
            <jet-input-error :message="form.errors.media_url" class="mt-2" />
          </div>

          <!-- Spotify URL -->
          <div class="col-span-6 sm:col-span-4">
            <jet-label for="spotify_url" value="Spotify URL" />
            <jet-input
              id="spotify_url"
              type="url"
              class="mt-1 block w-full"
              v-model="form.spotify"
              autocomplete="spotify_url"
              placeholder="Spotify URL"
            />
            <jet-input-error :message="form.errors.spotify" class="mt-2" />
          </div>

          <!-- Google Podcast URL -->
          <div class="col-span-6 sm:col-span-4">
            <jet-label for="google_podcast" value="Google Podcast URL" />
            <jet-input
              id="google_podcast"
              type="url"
              class="mt-1 block w-full"
              v-model="form.google_podcast"
              autocomplete="google_podcast"
              placeholder="Google Podcast URL"
            />
            <jet-input-error :message="form.errors.google_podcast" class="mt-2" />
          </div>

          <!-- Apple Podcast URL -->
          <div class="col-span-6 sm:col-span-4">
            <jet-label for="apple_podcast" value="Apple Podcast URL" />
            <jet-input
              id="apple_podcast"
              type="url"
              class="mt-1 block w-full"
              v-model="form.apple_podcast"
              autocomplete="apple_podcast"
              placeholder="Apple Podcast URL"
            />
            <jet-input-error :message="form.errors.apple_podcast" class="mt-2" />
          </div>

          <!-- tags -->
          <div class="col-span-6 sm:col-span-4">
            <jet-label for="tags" value="Tags" />
            <vue-tags-input
              class="mt-1 block w-full rounded-md max-w-max border-0"
              v-model="tag"
              :tags="form.tags"
              placeholder="Add Tags"
              @tags-changed="(newTags) => (form.tags = newTags)"
            />
            <jet-input-error :message="form.errors.tags" class="mt-2" />
          </div>

          <!-- featured hotsts -->
          <div class="col-span-6 sm:col-span-4">
            <jet-label for="featured_hosts" value="Featured Hosts" />
            <vue-tags-input
              class="mt-1 block w-full rounded-md max-w-max"
              v-model="featured_host"
              :tags="form.featured_hosts"
              placeholder="Add Names"
              @tags-changed="(newTags) => (form.featured_hosts = newTags)"
            />
            <jet-input-error
              :message="form.errors.featured_hosts"
              class="mt-2"
            />
          </div>

          <!-- categories -->
          <div class="col-span-6 sm:col-span-4">
            <jet-label for="categories" value="Categories" class="mb-1" />
            <Multiselect
              v-model="form.categories"
              mode="tags"
              :options="categories"
              searchable
              label="name"
              trackBy="name"
              valueProp="id"
              placeholder="Select Categories"
            />
            <jet-input-error :message="form.errors.categories" class="mt-2" />
          </div>

          <!-- is featured -->
          <div class="col-span-6 sm:col-span-4">
            <jet-label for="is_featured" value="Featured?" class="mb-1" />
            <Toggle v-model="form.is_featured" />
            <jet-input-error :message="form.errors.is_featured" class="mt-2" />
          </div>

          <!-- is featured -->
          <div class="col-span-6 sm:col-span-4">
            <jet-label for="is_popular" value="Popular?" class="mb-1" />
            <Toggle v-model="form.is_popular" />
            <jet-input-error :message="form.errors.is_popular" class="mt-2" />
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

          <Link :href="route('episodes.show')" class="ml-4">
            <jet-secondary-button :type="'button'">Cancel</jet-secondary-button>
          </Link>
        </template>
      </jet-form-section>
    </div>
  </app-layout>
  <show-select-modal
    :show="isOpenShowSelector"
    @update:show-changed="onShowChanged"
    @close="isOpenShowSelector = false"
  ></show-select-modal>
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
import ShowSelectModal from "@/jetstream/ShowSelect.vue";
import { Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { throttle } from "lodash";
import SelectedShow from "@/Jetstream/SelectedShow.vue";
import VueTagsInput from "@sipec/vue3-tags-input";
import Multiselect from "@vueform/multiselect";
import Toggle from "@vueform/toggle";

export default defineComponent({
  props: ["episode", "categories"],
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
    SelectedShow,
    ShowSelectModal,
    VueTagsInput,
    Multiselect,
    Toggle,
  },
  mounted() {
    if (this.episode) {
      this.form.name = this.episode.name;
      this.form.media_url = this.episode.media_url;
      this.form.google_podcast = this.episode.google_podcast;
      this.form.spotify = this.episode.spotify;
      this.form.apple_podcast = this.episode.apple_podcast;
      this.form.tags = this.episode.tags;
      this.form.featured_hosts = this.episode.featured_hosts;
      this.form.is_featured = this.computedIsFeatured;
      this.form.is_popular = this.computedIsPopular;
      this.form.description = this.episode.description;
      this.form.show_id = this.episode.show_id;
      this.form.categories = this.computedCategories;
    }
  },
  computed: {
    computedCategories() {
      let categoryIds = [];
      this.episode.categories.forEach((element) => {
        categoryIds.push(element.id);
      });
      return categoryIds;
    },
    computedIsFeatured() {
      return this.episode.is_featured == 1 ? true : false;
    },
    computedIsPopular() {
      return this.episode.is_popular == 1 ? true : false;
    },
  },
  data() {
    return {
      form: this.$inertia.form({
        name: "",
        media_url: "",
        spotify: "",
        apple_podcast: "",
        google_podcast: "",
        tags: [],
        featured_hosts: [],
        is_featured: false,
        is_popular: false,
        description: "",
        show_id: null,
        photo: null,
        categories: [],
      }),
      photoPreview: null,
      isOpenShowSelector: false,
      tag: "",
      featured_host: "",
      value: null,
    };
  },
  methods: {
    createUpdateEpisode() {
      if (this.episode === undefined) {
        if (this.$refs.photo) {
          this.form.photo = this.$refs.photo.files[0];
          this.form.post(route("episodes.store"), {
            errorBag: "createUpdateEpisode",
            preserveScroll: true,
          });
        }
      } else {
        if (this.$refs.photo) {
          this.form.photo = this.$refs.photo.files[0];
        } else {
          delete this.form.photo;
        }

        this.form.post(route("episodes.update", { id: this.episode.id }), {
          errorBag: "createUpdateEpisode",
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
    onShowChanged(id) {
      this.form.show_id = id;
    },
  },
});
</script>

<style>
.vue-tags-input {
  position: relative;
  background-color: #fff;
  width: 100%;
  max-width: unset !important;
}
.ti-input {
  border-radius: 0.375rem !important;
}
.ti-tag {
  background-color: #1f2937 !important;
}
.ti-selected-item {
  background-color: #1f2937 !important;
}
.ti-new-tag-input-wrapper > [type="text"]:focus {
  --tw-ring-color: rgba(0, 0, 0, 0);
  --tw-ring-shadow: rgba(0, 0, 0, 0);
}
.multiselect-tags-search-wrapper > [type="text"]:focus {
  --tw-ring-color: rgba(0, 0, 0, 0);
  --tw-ring-shadow: rgba(0, 0, 0, 0);
}
</style>

