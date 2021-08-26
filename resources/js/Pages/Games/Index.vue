<template>
  <app-layout title="登録者一覧">
    <template #header>
      <h2 class="font-semibold text-gray-600 leading-tight"></h2>
    </template>

    <div class="flex flex-row py-36">
      <div class="max-w-7xl mx-auto px-6 px-8 w-5/12">
        <div
          class="bg-gray-600 text-white py-2 my-8 mr-4 shadow-xl w-full text-center font-bold"
        >
          ゲーム一覧
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
                          No
                        </th>
                        <th
                          scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          MAP
                        </th>
                      </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                      <tr
                        v-for="data in datas"
                        :key="data.id"
                        @click="select(data)"
                        :class="{ 'bg-yellow-400': data.selected }"
                        class="cursor-pointer"
                      >
                        <td class="px-6 py-2 whitespace-nowrap">
                          <div class="text-sm font-medium text-gray-900">
                            {{ data.id }}
                          </div>
                        </td>
                        <td class="px-6 py-2 whitespace-nowrap">
                          <div class="text-sm text-gray-900">
                            {{ maps[data.map] }}
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
      <div class="w-7/12 bg-gray-400 h-full shadow-xl rounded-sm mx-6">
        <div class="flex p-4 pt-6">
          <div class="w-1/2">
            <div class="mx-2">
              <div
                class="bg-red-600 text-center py-2 text-white text-base font-bold my-8 shadow-xl rounded-sm w-full"
              >
                Aチーム
              </div>
              <div
                v-for="playerA in teamA"
                :key="playerA.id"
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
                :key="playerB.id"
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
        <div
          class="text-center m-6 py-6 bg-white shadow-xl rounded-sm"
          v-show="isData"
        >
          <div
            class="text-4xl font-bold border-b-4 pb-2"
            :class="{
              'border-red-600': resultColor.teamAWin.isWin,
              'border-blue-600': resultColor.teamBWin.isWin,
              'border-green-600': resultColor.drow.isWin,
            }"
          >
            {{ result }}
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
    datas: Object,
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
      bgColor: [
        { "border-color": "#deb887" },
        { "border-color": "#f5f5f5" },
        { "border-color": "#ffd700" },
        { "border-color": "#c0c0c0" },
        { "border-color": "#87cefa" },
        { "border-color": "#ffa500" },
        { "border-color": "#fffacd" },
      ],
      teamA: [],
      teamB: [],
      resultColor: {
          teamAWin: {isWin : false, message: "Aチーム WIN !!"},
          teamBWin: {isWin : false, message: "Bチーム WIN !!"},
          drow: {isWin : false, message: "引き分け"},
      },
    };
  },

  created() {
      console.log(this.datas)
    //selectedをreduceでdatasに入れる
    if (this.datas.length > 0) {
      this.teamA.splice(0);
      this.teamB.splice(0);
      this.datas.forEach(element => {
          element.selected = false
      });

      const lastGame = this.datas[this.datas.length - 1];

      lastGame.selected = true;

      this.teamA = lastGame.players.slice(0,6);
      this.teamB = lastGame.players.slice(6,12);

      this.selectColor(lastGame)
    }
  },

  computed: {
      result() {
          return this.resultColor.teamAWin.isWin ? this.resultColor.teamAWin.message : this.resultColor.teamBWin.isWin ? this.resultColor.teamBWin.message : this.resultColor.drow.message
      },
      isData() {
          return (this.datas.length > 0)
      }

  },

  methods: {
    select(data) {
      this.teamA.splice(0);
      this.teamB.splice(0);
      this.datas.forEach((element) => (element.selected = false));

      data.selected = true;

      this.teamA = data.players.slice(0,6);
      this.teamB = data.players.slice(6,12);

      this.selectColor(data)
    },
    selectColor(game) {
        Object.keys(this.resultColor).forEach((key) => {
            this.resultColor[key].isWin = false;
        });
        if(game.result == 0) {
            this.resultColor.teamAWin.isWin = true;
        } else if(game.result == 1) {
            this.resultColor.teamBWin.isWin = true;
        } else if(game.result == 2){
            this.resultColor.drow.isWin = true;
        }
    }
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
