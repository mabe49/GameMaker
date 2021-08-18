<template>
  <app-layout title="登録者一覧">
    <template #header>
      <h2 class="font-semibold text-gray-600 leading-tight">
        <!-- 観戦プレイヤー登録 -->
      </h2>
    </template>

    <div class="py-12 flex flex-row">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 w-5/12">
        <div class="flex justify-end">
          <jet-button
            @click.native="resetTeam"
            class="bg-blue-600 text-base my-8 mr-4 shadow-xl"
            :disabled="disabled"
            >リセット</jet-button
          >
          <jet-button
            @click.native="makeTeam"
            class="bg-green-600 text-base my-8 shadow-xl"
            :disabled="disabled"
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
                  class="shadow overflow-hidden border-b border-gray-200 sm:rounded-sm"
                >
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr>
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
                          SKILL-RATE
                        </th>
                        <th
                          scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          SPECTATE
                        </th>
                      </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                      <tr
                        v-for="player in players"
                        :key="player.id"
                        @click="addSpectate(player.id)"
                        :class="{ 'bg-gray-300': selected[player.id] }"
                      >
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
                              player.game_count - player.win_count
                            }}</span>
                          </div>
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
                        <td class="px-6 py-2 whitespace-nowrap">
                          <div class="text-sm font-medium text-gray-900 ml-4">
                            {{ player.rest_count }}
                          </div>
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
      <div class="w-5/12 px-6 bg-gray-400 shadow-xl rounded-sm">
        <div class="h-full flex">
          <div class="w-1/2">
            <div class="h-full mx-2">
              <div
                class="bg-red-600 text-center py-2 text-white text-base font-bold my-8 shadow-xl rounded-sm w-full"
              >
                チームA
              </div>
              <div
                v-for="playerB in teamB"
                :key="playerB.index"
                class="bg-white text-center py-2 text-base font-bold my-8 shadow-xl rounded-sm w-full"
              >
                <div>{{ playerB.name }}</div>
                <div
                  class="px-2 inline-flex text-xs leading-5 font-semibold w-full h-8 grid place-items-center border-b-4"
                  :style="bgColor[playerB.player_strength]"
                >
                  {{ skill_rate[playerB.player_strength] }}
                </div>
              </div>
            </div>
          </div>
          <div class="w-1/2">
            <div class="h-full mx-2">
              <div
                class="bg-blue-600 text-center py-2 text-white text-base font-bold my-8 shadow-xl rounded-sm w-full"
              >
                チームB
              </div>
              <div
                v-for="playerA in teamA"
                :key="playerA.index"
                class="bg-white text-center py-2 text-base font-bold my-8 shadow-xl rounded-sm w-full"
              >
                <div>{{ playerA.name }}</div>
                <div
                  class="px-2 inline-flex text-xs leading-5 font-semibold w-full h-8 grid place-items-center border-b-4"
                  :style="bgColor[playerA.player_strength]"
                >
                  {{ skill_rate[playerA.player_strength] }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="w-2/12 mx-2 px-4">
        <div class="h-auto shadow-xl bg-gray-400 px-2 pb-2 rounded-sm">
          <div class="flex justify-center">
            <div
              class="bg-gray-600 text-center py-2 text-white text-base font-bold my-8 shadow-xl rounded-sm w-full"
            >
              観戦プレイヤー
            </div>
          </div>
          <div
            v-for="spectatePlayer in spectatePlayers"
            :key="spectatePlayer.index"
            class="bg-white text-center py-2 text-base font-bold mb-2 shadow-xl rounded-sm w-full"
          >
            <div>{{ spectatePlayer.name }}</div>
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
      show: true,
      editId: null,
      selected: [],
      spectatePlayers: [],
      teamA: [],
      teamB: [],
      participatePlayers: Object.assign([], this.players),
      disabled: true,
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
      return this.players.filter((element) => element.attendance == 1).length;
    },
  },

  methods: {
    addSpectate(id) {
      if (this.selected[id]) {
        this.selected[id] = false;
        this.participatePlayers = this.participatePlayers.concat(
          this.spectatePlayers.filter((element) => element.id == id)
        );
        this.spectatePlayers.splice(
          this.spectatePlayers.findIndex((element) => element.id == id),
          1
        );
      } else {
        this.selected[id] = true;
        this.spectatePlayers = this.spectatePlayers.concat(
          this.participatePlayers.filter((element) => element.id == id)
        );
        this.participatePlayers.splice(
          this.participatePlayers.findIndex((element) => element.id == id),
          1
        );
      }

      if (this.participatePlayers.length == 12) {
        this.disabled = false;
      } else {
        this.disabled = true;
      }
    },
    makeTeam() {
      this.teamA.splice(0);
      this.teamB.splice(0);
      this.participatePlayers.sort(function (a, b) {
        if (a.win_count / a.game_count > b.win_count / a.game_count) return -1;
        if (a.win_count / a.game_count < b.win_count / a.game_count) return 1;
        if (a.player_strength > b.player_strength) return -1;
        if (a.player_strength < b.player_strength) return 1;
        return 0;
      });
      this.teamA = this.teamA.concat(
        this.participatePlayers[0],
        this.participatePlayers[3],
        this.participatePlayers[4],
        this.participatePlayers[7],
        this.participatePlayers[8],
        this.participatePlayers[11]
      );
      this.teamB = this.teamB.concat(
        this.participatePlayers[1],
        this.participatePlayers[2],
        this.participatePlayers[5],
        this.participatePlayers[6],
        this.participatePlayers[9],
        this.participatePlayers[10]
      );
      console.table(this.teamA);
      console.table(this.teamB);
    },
    resetTeam() {
      this.spectatePlayers.splice(0);
      this.participatePlayers.splice(0);
      this.participatePlayers = Object.assign([], this.players);
      this.teamA.splice(0);
      this.teamB.splice(0);
      this.selected.splice(0);
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
