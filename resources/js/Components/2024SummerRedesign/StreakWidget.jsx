import StatisticsTile from "./StatisticsTile";

export default function StreakWidget({streak, active}){

    // Days on which we will show a fancy UI
    const specialDays = [1, 7, 10, 14, 30, 180];

    const isSpecial = active && (specialDays.includes(streak) || (streak != 0 && (streak % 100 == 0 || streak % 365 == 0)));

    return <StatisticsTile labelColor={isSpecial ? "#FFDB00" : null} style={{backgroundColor: isSpecial ? "#FF7F3E" : null}} iconColor={isSpecial ? "#FFBF00" : "#F3AF71"} disabled={!active} stat={streak} label={"Järjestikust päeva"} oneLabel={"Järjestikune päev"} icon={"local_fire_department"} />;
}