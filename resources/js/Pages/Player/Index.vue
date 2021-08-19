<template>
  <app-layout title="登録者一覧">
    <template #header>
      <h2 class="font-semibold text-gray-600 leading-tight"></h2>
    </template>

    <jet-dialog-modal :show="showCreater" @close="closeCreater">
      <template #title> 新規作成 </template>
      <template #content>
        <form @submit.prevent="submit">
          <div>
            <jet-label for="name" value="NAME"></jet-label>
            <jet-input
              type="text"
              id="name"
              class="mt-1 block w-1/2"
              v-model="form.name"
              autofocus
            ></jet-input>
            <jet-input-error :message="form.errors.name"></jet-input-error>
          </div>
          <div class="mt-4">
            <jet-label for="player_strength" value="SKILL-RATE"></jet-label>
            <select
              id="player_strength"
              class="mt-1 block w-1/2 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
              v-model="form.player_strength"
              autofocus
            >
              <option value="0">ブロンズ</option>
              <option value="1">シルバー</option>
              <option value="2">ゴールド</option>
              <option value="3">プラチナ</option>
              <option value="4">ダイヤ</option>
              <option value="5">マスター</option>
              <option value="6">グランドマスター</option>
            </select>
            <jet-input-error
              :message="form.errors.player_strength"
            ></jet-input-error>
          </div>
          <div class="mt-8 flex">
            <jet-checkbox
              name="attendance"
              v-model:checked="form.attendance"
            ></jet-checkbox>
            <jet-label class="ml-2" for="attendance" value="参加"></jet-label>
          </div>
          <div class="mt-4 flex justify-end">
            <jet-danger-button class="mr-2" @click="closeCreater"
              >キャンセル</jet-danger-button
            >
            <jet-button :disabled="form.processing">作成</jet-button>
          </div>
        </form>
      </template>
    </jet-dialog-modal>

    <jet-dialog-modal :show="showEditor" @close="closeEditor">
      <template #title> プレイヤー編集 </template>
      <template #content>
        <form @submit.prevent="edit(form)">
          <div>
            <jet-label for="name" value="NAME"></jet-label>
            <jet-input
              type="text"
              id="name"
              class="mt-1 block w-1/2"
              v-model="form.name"
              autofocus
            ></jet-input>
            <jet-input-error :message="form.errors.name"></jet-input-error>
          </div>
          <div class="mt-4">
            <jet-label for="player_strength" value="SKILL-RATE"></jet-label>
            <select
              id="player_strength"
              class="mt-1 block w-1/2 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
              v-model="form.player_strength"
              autofocus
            >
              <option value="0">ブロンズ</option>
              <option value="1">シルバー</option>
              <option value="2">ゴールド</option>
              <option value="3">プラチナ</option>
              <option value="4">ダイヤ</option>
              <option value="5">マスター</option>
              <option value="6">グランドマスター</option>
            </select>
            <jet-input-error
              :message="form.errors.player_strength"
            ></jet-input-error>
          </div>
          <div class="mt-8 flex">
            <jet-checkbox
              id="attendance"
              v-model:checked="form.attendance"
            ></jet-checkbox>
            <jet-label class="ml-2" for="attendance" value="参加"></jet-label>
          </div>
          <div class="mt-4 flex justify-end">
            <jet-danger-button class="mr-2" @click="closeEditor"
              >キャンセル</jet-danger-button
            >
            <jet-button :disabled="form.processing">更新</jet-button>
          </div>
        </form>
      </template>
    </jet-dialog-modal>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-end">
          <jet-button
            @click="showCreater = true"
            class="bg-blue-600 text-base my-8 shadow-xl"
            >新規作成</jet-button
          >
        </div>
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
              <div
                class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"
              >
                <div
                  class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg"
                >
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          total:<span class="font-bold">{{ total }}</span>
                        </th>
                        <th
                          scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          NAME
                        </th>
                        <th
                          scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          WIN-LOSE
                        </th>
                        <th
                          scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          WIN-RATE
                        </th>
                        <th
                          scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          SKILL-RATE
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                          <span class="sr-only">Edit</span>
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                          <span class="sr-only">Delete</span>
                        </th>
                      </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                      <tr v-for="player in players" :key="player.id" class="hover:bg-gray-200 cursor-pointer">
                        <td
                          class="px-6 py-2 whitespace-nowrap text-sm font-medium"
                        >
                          <span v-if="player.attendance" class="text-blue-500 ml-4"
                            >●</span
                          >
                        </td>
                        <td class="px-6 py-2 whitespace-nowrap">
                          <div class="text-sm font-medium text-gray-900">
                            {{ player.name }}
                          </div>
                        </td>
                        <td class="px-6 py-2 whitespace-nowrap">
                          <div class="text-sm text-gray-900">
                            <span class="text-red-500">{{
                              player.win_count
                            }}</span>
                            -
                            <span class="text-blue-500">{{
                              player.lose_count
                            }}</span>
                            -
                            <span class="text-gray-500">{{
                              player.game_count - player.win_count -player.lose_count
                            }}</span>
                          </div>
                        </td>
                        <td class="px-6 py-2 whitespace-nowrap">
                          <span
                            class="px-2 inline-flex text-xs leading-5 font-semibold"
                          >
                            <div v-if="player.game_count">
                              {{
                                parseInt(
                                  (player.win_count / player.game_count) * 100,
                                  10
                                )
                              }}
                              %
                            </div>
                            <div v-else>0 %</div>
                          </span>
                        </td>
                        <td class="px-6 py-2 whitespace-nowrap text-sm">
                          <div
                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full w-32 h-8 grid place-items-center border-4"
                            :style="bgColor[player.player_strength]"
                          >
                            <span>
                              {{ skill_rate[player.player_strength] }}
                            </span>
                          </div>
                        </td>
                        <td
                          class="px-6 py-2 whitespace-nowrap text-right text-sm font-medium"
                        >
                          <button
                            @click="openEditModal(player)"
                            class="text-green-600 hover:text-green-900"
                          >
                            編集
                          </button>
                        </td>
                        <td
                          class="px-6 py-2 whitespace-nowrap text-right text-sm font-medium"
                        >
                          <button
                            @click="deletePlayer(player.id)"
                            class="text-red-600 hover:text-red-900"
                          >
                            削除
                          </button>
                        </td>
                      </tr>

                      <!-- More people... -->
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import JetButton from "@/Jetstream/Button";
import JetDangerButton from "@/Jetstream/DangerButton";
import JetDialogModal from "@/Jetstream/DialogModal";
import JetInput from "@/Jetstream/Input";
import JetLabel from "@/Jetstream/Label";
import JetInputError from "@/Jetstream/InputError";
import JetCheckbox from "@/Jetstream/Checkbox";

export default {
  props: {
    players: Object,
  },

  data() {
    return {
      skill_rate: [
        "BRONZE",
        "SILVER",
        "GOLD",
        "PLATINUM",
        "DIAMOND",
        "MASTER",
        "GRAND MASTER",
      ],
      showCreater: false,
      showEditor: false,
      editId: null,
      form: this.$inertia.form({
        name: "",
        player_strength: "",
        attendance: false,
      }),
      bgColor: [
        { "border-color": "#deb887" },
        { "border-color": "#f5f5f5" },
        { "border-color": "#ffd700" },
        { "border-color": "#c0c0c0" },
        { "border-color": "#87cefa" },
        { "border-color": "#ffa500" },
        { "border-color": "#fffacd" },
      ],
    };
  },
  computed: {
      total() {
          return (this.players.filter(element => element.attendance == 1)).length;
      },

    },

  methods: {

    closeCreater() {
      this.showCreater = false;
      this.form.clearErrors();
      this.form.reset();
    },
    closeEditor() {
      this.showEditor = false;
      this.form.clearErrors();
      this.form.reset();
    },
    openEditModal(player) {
      this.showEditor = true;
      this.editId = player.id,
      this.form.name = player.name;
      this.form.player_strength = player.player_strength;
      this.form.attendance = Boolean(player.attendance);
    },
    submit() {
      console.log(this.form);
      this.form.post(route("player.store"), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
          this.showCreater = false;
          this.form.reset();
        },
        onError: () => (this.showCreater = true),
      });
    },
    edit() {
      this.form.put(route("player.update", this.editId), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
          this.showEditor = false;
          this.form.reset();
        },
        onError: () => (this.showEditor = true),
      });
    },
    deletePlayer(id) {
      this.form.delete(route("player.destroy", id), {
        preserveScroll: true,
      });
    },
  },

  components: {
    AppLayout,
    JetButton,
    JetDangerButton,
    JetDialogModal,
    JetInput,
    JetInputError,
    JetLabel,
    JetCheckbox,
  },
};
</script>
