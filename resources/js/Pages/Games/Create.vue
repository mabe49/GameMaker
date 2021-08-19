<template>
  <app-layout title="登録者一覧">
    <template #header>
      <h2 class="font-semibold text-gray-600 leading-tight">
        <!-- 観戦プレイヤー登録 -->
      </h2>
    </template>

    <jet-dialog-modal :show="show" @close="show = !show">
      <template #title> 勝敗登録 </template>
      <template #content>
        <div class="inline-block">
          <jet-label for="map" value="マップ"></jet-label>
          <select v-model="form.map">
            <option v-for="(map, index) in maps" :key="index" :value="index">
              {{ map }}
            </option>
          </select>
        </div>

        <div class="flex justify-center">
          <jet-button
            @click.native="createGame(0)"
            class="bg-red-500 text-center py-2 text-white text-base font-bold my-8 shadow-xl rounded-sm mx-4"
            >Aチーム</jet-button
          >
          <jet-button
            @click.native="createGame(1)"
            class="bg-blue-500 text-center py-2 text-white text-base font-bold my-8 shadow-xl rounded-sm mx-4"
            >Bチーム</jet-button
          >
          <jet-button
            @click.native="createGame(2)"
            class="bg-green-500 text-center py-2 text-white text-base font-bold my-8 shadow-xl rounded-sm mx-4"
            >引き分け</jet-button
          >
        </div>
      </template>
    </jet-dialog-modal>

    <div class="py-12 flex flex-row">
      <div class="max-w-7xl mx-auto px-6 px-8 w-5/12">
        <span class="text-red-500 text-sm font-bold"
          >※{{ messages[state] }}</span
        >
        <div class="flex justify-end">
          <jet-button
            @click.native="resetTeam"
            class="bg-blue-600 text-base my-8 mr-4 shadow-xl"
            >リセット</jet-button
          >
          <jet-button
            @click.native="makeTeam"
            class="bg-green-600 text-base my-8 shadow-xl"
            :disabled="disabled"
            >チーム作成</jet-button
          >
        </div>
        <div class="bg-white overflow-hidden shadow-xl rounded-lg">
          <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto -mx-8">
              <div class="py-2 align-middle inline-block min-w-full px-8">
                <div
                  class="shadow overflow-hidden border-b border-gray-200 rounded-sm"
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
                        class="hover:bg-gray-300 cursor-pointer"
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
      <div class="w-5/12 rounded-sm">
        <div class="flex flex-row-reverse">
          <jet-button
            @click.native="openModal"
            class="bg-yellow-400 text-center py-2 text-white text-base font-bold my-8 shadow-xl rounded-sm"
            :disabled="state != 2"
            >勝敗登録</jet-button
          >
        </div>
        <div class="flex bg-gray-400 h-full shadow-xl rounded-sm p-4 pt-6">
          <div class="w-1/2">
            <div class="mx-2">
              <div
                class="bg-red-600 text-center py-2 text-white text-base font-bold my-8 shadow-xl rounded-sm w-full"
              >
                Aチーム
              </div>
              <div
                v-for="playerA in teamA"
                :key="playerA.index"
                class="bg-white text-center py-3 text-base font-bold my-4 h-18 shadow-xl rounded-sm w-full"
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
          <div class="w-1/2">
            <div class="mx-2">
              <div
                class="bg-blue-600 text-center py-2 text-white text-base font-bold my-8 shadow-xl rounded-sm w-full"
              >
                Bチーム
              </div>
              <div
                v-for="playerB in teamB"
                :key="playerB.index"
                class="bg-white text-center py-3 text-base font-bold my-4 h-18 shadow-xl rounded-sm w-full"
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
        </div>
      </div>
      <div class="w-2/12 mx-2 px-4">
        <div class="h-auto px-6 pb-2 rounded-sm">
          <div class="flex justify-center">
            <div
              class="text-center py-2 text-white text-base font-bold my-8 w-full bg-gray-600 rounded-sm shadow-xl"
            >
              観戦プレイヤー
            </div>
          </div>
          <div>
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
      messages: [
        "観戦者をクリックして登録",
        "チーム作成をクリックしてチーム分け",
        "勝敗登録で試合結果を登録",
      ],
      show: false,
      state: 0,
      editId: null,
      selected: [],
      spectatePlayers: [],
      teamA: [],
      teamB: [],
      participatePlayers: Object.assign([], this.players),
      disabled: true,
      form: this.$inertia.form({
        map: 0,
        aPlayers: [],
        bPlayers: [],
        sPlayers: [],
        result: null,
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
      maps: [
        "BLIZZARD WORLD",
        "BUSAN",
        "DORADO",
        "EICHENWALDE",
        "HANAMURA",
        "HAVANA",
        "HOLLYWOOD",
        "HORIZON LUNAR COLONY",
        "ILIOS",
        "JUNKERTOWN",
        "KING'S ROW",
        "LIJIANG TOWER",
        "NEPAL",
        "NUMBANI",
        "OASIS",
        "PARIS",
        "RIALTO",
        "ROUTE 66",
        "TEMPLE OF ANUBIS",
        "VOLSKAYA INDUSTRIES",
        "WATCHPOINT: GIBRALTAR",
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
        if (this.state == 0) {
          this.selected[id] = true;
          this.spectatePlayers = this.spectatePlayers.concat(
            this.participatePlayers.filter((element) => element.id == id)
          );
          this.participatePlayers.splice(
            this.participatePlayers.findIndex((element) => element.id == id),
            1
          );
        }
      }

      if (this.participatePlayers.length == 12) {
        this.disabled = false;
        this.state = 1;
      } else {
        this.disabled = true;
        this.state = 0;
      }
    },
    makeTeam() {
      this.state = 2;
      this.teamA.splice(0);
      this.teamB.splice(0);
      this.participatePlayers.sort(function (a, b) {
        //優先をスキルレートにするか勝率にするか
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
    },
    resetTeam() {
      this.state = 0;
      this.spectatePlayers.splice(0);
      this.participatePlayers.splice(0);
      this.participatePlayers = Object.assign([], this.players);
      this.teamA.splice(0);
      this.teamB.splice(0);
      this.selected.splice(0);
    },
    openModal() {
      this.show = true;
      this.form.aPlayers.splice(0);
      this.form.bPlayers.splice(0);

      this.teamA.forEach((element) => {
        this.form.aPlayers = this.form.aPlayers.concat(element.id);
      });
      this.teamB.forEach((element) => {
        this.form.bPlayers = this.form.bPlayers.concat(element.id);
      });
        this.spectatePlayers.forEach((element) => {
          this.form.sPlayers = this.form.sPlayers.concat(element.id);
        });
    },

    createGame(id) {
      this.show = false;
      this.form.result = id;
      console.log(this.form);
      this.form.post(route("game.store"));
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
