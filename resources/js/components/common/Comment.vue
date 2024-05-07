<template>
  <div class="content">
    <div class="content__text">
      <Mentionable
        :keys="['@']"
        :items="items"
        offset="10"
        filtering-disabled
        insert-space
        @open="loadUsers()"
        @search="loadUsers($event)"
      >
        <textarea
          @keyup.enter="addCommentByEnter"
          class="content__text--textarea focus:ring-0"
          v-model="comment"
          rows="3"
          :placeholder="$t('activity-log.placeholders.add_comment')"
        ></textarea>
        <template #no-result>
          <div class="dim">
            {{
              loading
                ? $t("activity-log.fields.loading")
                : $t("activity-log.fields.no_result")
            }}
          </div>
        </template>

        <template #item-@="{ item }">
          <div class="mention-wrapper">
            <div
              class="mention-wrapper--avatar activity__user--avatar !w-[12px] !h-[12px]"
            >
              <span class="font-bold !text-lg">{{
                item.label.charAt(0).toUpperCase() +
                item.label.charAt(1).toUpperCase()
              }}</span>
            </div>
            <span class="dim">
              {{ item.label }}
            </span>
          </div>
        </template>
      </Mentionable>
    </div>

    <div class="content__action">
      <div class="content__action-attachment"></div>
      <div
        class="content__action-button cursor-pointer"
        :class="{ 'content__action-button--disable': !comment }"
        @click="addComment"
      >
        {{ $t("activity-log.buttons.save_comment") }}
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { Mentionable } from "vue-mention";

export default {
  name: "Comment",
  components: {
    Mentionable,
  },
  props: {
    enterToComment: {
      type: Boolean,
      default: false,
    },
    commentUrl: {
      type: String,
      default: "/api/generic/activity-logs/comments",
    },
    modelClass: {
      type: String,
      required: true,
    },
    modelId: {
      type: String,
      required: true,
    },
    user: {
      type: String,
      required: false,
    },
  },
  data() {
    return {
      loading: false,
      comment: null,
      items: [],
    };
  },
  methods: {
    async loadUsers(searchText = null) {
      this.loading = true;
      axios
        .get(
          `/generic/api/activity-logs/filters/facets/created_by?s=${searchText}`,
        )
        .then((res) => {
          this.items = res.data.map((item) => {
            return {
              label: item.label,
              value: `[${item.label}]`,
              id: item.value,
            };
          });
          this.loading = false;
        });
    },
    addCommentByEnter() {
      if (this.enterToComment) {
        this.addComment();
      }
    },
    addComment() {
      if (!this.comment) {
        return;
      }
      axios
        .post(this.commentUrl, {
          modelClass: this.modelClass,
          modelId: this.modelId,
          comment: this.comment,
          currentUrl: window.location.href,
          currentUser: this.user,
        })
        .then(({ data }) => {
          this.comment = null;
          this.$emit("addComment", data.data);
        })
        .catch(console.error);
    },
  },
};
</script>

<style scoped>
.mention-wrapper {
  display: flex;
  padding: 6px;
  border-radius: 4px;
  gap: 8px;
  cursor: pointer;
  line-height: 20px;
  font-size: 14px;
}
.mention-wrapper--avatar {
  padding: 6px;
  margin-left: 10px;
}

.mention-selected .mention-wrapper {
  background: rgba(226, 232, 240, 1);
}

.mention-wrapper .number {
  font-family: monospace;
}

.dim {
  padding: 10px;
  color: #666;
}
</style>