import Chip from "@/Components/2024SummerRedesign/Chip";
import Layout from "@/Components/2024SummerRedesign/Layout";
import StatisticsTile from "@/Components/2024SummerRedesign/StatisticsTile";
import StreakWidget from "@/Components/2024SummerRedesign/StreakWidget";
import TwoRowTextButton from "@/Components/2024SummerRedesign/TwoRowTextButton";
import { useEffect, useRef, useState } from "react";

export default function StatsPage({auth, stats}){
    
    function averageTime(timeInSeconds){
        var minutes = Math.floor(timeInSeconds / 60);
        var seconds = timeInSeconds - 60*minutes;

        minutes = minutes <= 9 ? "0"+minutes.toString() : minutes.toString();
        seconds = seconds <= 9 ? "0"+seconds.toString() : seconds.toString();

        return minutes + ":" + seconds;
    }    

    const [statsPeriod, setStatsPeriod] = useState("week");
    const canvasRef = useRef(null);

    const periods = {"week":"nädal", "month":"kuu", "year": "aasta"};

    const gameNames = {
        "lihtsustamine":"Murru taandamine",
        "murruTaandamine":"Murru taandamine",
        "jaguvus":"Jaguvusseadused"
    };

    var gameName = (game) => game == null ? "Tundmatu" : game in gameNames ? gameNames[game] : game.substring(0, 1).toUpperCase() + game.substring(1);



    const chartPaddingTop = 20;     // space above the tallest point
    const chartPaddingBottom = 20;  // space below the lowest point

    function drawChart() {
        const canvas = canvasRef.current;
        if (!canvas) return;
        const ctx = canvas.getContext('2d');

        // Measure CSS size and set min width
        let rect = canvas.getBoundingClientRect();
        const cssWidth = Math.max(rect.width, 200); // at least 200px wide
        const cssHeight = rect.height;

        // High-DPI buffer
        const dpr = window.devicePixelRatio || 1;
        canvas.width = cssWidth * dpr;
        canvas.height = cssHeight * dpr;
        ctx.scale(dpr, dpr);

        const points = stats.gameCount[statsPeriod];
        const stepX = cssWidth / (points.length - 1);

        const minY = Math.min(...points);
        const maxY = Math.max(...points);
        const centerY = (maxY + minY) / 2;

        function scaleY(value) {
            return (
            chartPaddingTop +
            ((maxY - value) / (maxY - minY)) *
                (cssHeight - chartPaddingTop - chartPaddingBottom)
            );
        }

        ctx.clearRect(0, 0, cssWidth, cssHeight);

        // Draw smooth curve
        ctx.beginPath();
        ctx.moveTo(0, scaleY(points[0]));
        for (let i = 0; i < points.length - 1; i++) {
            const xMid = stepX * (i + 0.5);
            const yMid = (scaleY(points[i]) + scaleY(points[i + 1])) / 2;
            ctx.quadraticCurveTo(stepX * i, scaleY(points[i]), xMid, yMid);
        }
        ctx.lineTo(stepX * (points.length - 1), scaleY(points[points.length - 1]));

        const primaryColor = getComputedStyle(document.documentElement)
        .getPropertyValue('--primary-color').trim();


        ctx.strokeStyle = `rgb(${primaryColor})`;
        ctx.lineWidth = 2;
        ctx.stroke();

        // Fill under curve
        ctx.lineTo(cssWidth, cssHeight);
        ctx.lineTo(0, cssHeight);
        ctx.closePath();
        ctx.fillStyle = `rgb(${primaryColor}, 0.2)`;
        ctx.fill();

        // Labels
        const paddingLeft = 20;
        const paddingBlock = 10;
        ctx.font = '14px sans-serif';
        ctx.fillStyle = 'grey';
        ctx.textAlign = 'right';
        ctx.textBaseline = 'middle';
        ctx.fillText(maxY, paddingLeft - 5, scaleY(maxY) + 5 + paddingBlock);
        //   ctx.fillText(centerY, paddingLeft - 5, scaleY(centerY) + 5);
        ctx.fillText(minY, paddingLeft - 5, scaleY(minY) + 5 - paddingBlock);
    }

    function showCalendar(){
        const monthYear = document.getElementById('monthYear');
        const daysContainer = document.getElementById('days');
        let currentDate = new Date();

        const highlightedDays = stats.streakDays;
        
        const today = new Date();

        function renderCalendar(date) {
            const year = date.getFullYear();
            const month = date.getMonth();
            monthYear.textContent = date.toLocaleString('et-EE', { month: 'long', year: 'numeric' });

            const firstDay = new Date(year, month, 1).getDay(); // 0-6
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            daysContainer.innerHTML = '';

            // Fill initial empty cells
            for (let i = 0; i < firstDay; i++) {
            daysContainer.appendChild(document.createElement('div'));
            }

            // Fill days
            for (let day = 1; day <= daysInMonth; day++) {
            const dayElem = document.createElement('div');
            dayElem.textContent = day;
            dayElem.classList.add('day');
                const dayOfWeek = new Date(year, month, day).getDay();
            if (dayOfWeek === 5 || dayOfWeek === 6) { // Sunday(0) or Saturday(6)
                dayElem.classList.add('weekend');
            }

            if (
                day === today.getDate() &&
                month === today.getMonth() &&
                year === today.getFullYear()
            ) {
                dayElem.classList.add('today');
            }   
            
            var targetDate = [day, month+1, year];
            
            if (highlightedDays.some(date => date.length === targetDate.length && date.every((value, index) => value === targetDate[index]))) {
                dayElem.classList.add('highlight');
            }
            
            daysContainer.appendChild(dayElem);
            }
        }

        document.getElementById('prev').addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar(currentDate);
        });

        document.getElementById('next').addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar(currentDate);
        });

        renderCalendar(currentDate);
    }

    useEffect(() => {
        drawChart();
        window.addEventListener('resize', drawChart);
        showCalendar();
    }, [statsPeriod]);

    console.log(stats);
    

    return <>
    <style>
    {`
    .calendar { width: 90%; text-align: center; margin:auto }
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
    .header button { background: none; border: none; font-size: 18px; cursor: pointer; color: rgb(var(--text-color)) }
    .days { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; }
    .day { padding: 8px; border-radius: 4px; }
    .highlight { background: rgb(255, 127, 62); color: white; margin-inline: -5px;}
    .weekend { color: var(--grey-color); }
    .today{font-weight: bold;}
    `}
    </style>
        <Layout title={"Statistika"} auth={auth}>
            <div className="four-stat-row">
                <StatisticsTile stat={stats.total_training_count ?? totalTrainingCount} label={"Mängu"} oneLabel={"Mäng"} icon={"sports_esports"} />
                <StatisticsTile stat={(stats.accuracy ??(parseInt(window.localStorage.getItem("total-percentage") ?? "0")/parseInt(window.localStorage.getItem("total-training-count") ?? "1")).toFixed(0)) + "%"} label={"Vastamistäpsus"}icon={"percent"} />
                <StatisticsTile stat={averageTime(stats.average_time)} label={"Keskmine mänguaeg"}icon={"hourglass_bottom"} />
                <StatisticsTile stat={stats.competitionCount} label={"Võistlust kokku"} oneLabel={"Võistlus kokku"} icon={"leaderboard"} />
            </div>

            <div className="two-column-layout">
                <div>
                    <div className="section">
                        <TwoRowTextButton showArrow={false} upperText={"Statistika periood"} lowerText={"Viimane " + periods[statsPeriod]} />
                        <div>
                            <Chip onClick={()=>setStatsPeriod("week")} active={statsPeriod=="week"} label={"Nädal"} />
                            <Chip onClick={()=>setStatsPeriod("month")} active={statsPeriod=="month"} label={"Kuu"} />
                            <Chip onClick={()=>setStatsPeriod("year")} active={statsPeriod=="year"} label={"Aasta"} />
                        </div>
                    </div>

                    <div className="section" style={{position:"relative"}}>
                        <TwoRowTextButton showArrow={false} upperText={"Mängukorrad"} lowerText={stats.gameCount[statsPeriod].reduce((sum, count) => sum + count, 0) + " mängukord"+(stats.gameCount[statsPeriod].reduce((sum, count) => sum + count, 0) == 1 ? "" : "a")} />
                        {stats.gameCount[statsPeriod].every(e=>e==0) && <p style={{color:"var(--grey-color)", margin:"8px"}}>Sellel ajaperioodil ei ole ühtegi mängu mängitud</p>}
                        {!stats.gameCount[statsPeriod].every(e=>e==0) && <canvas ref={canvasRef} id="chart" style={{width:"90%", height:"200px", margin:"0 16px"}}></canvas>}
                    </div>

                    <div className="section">
                        <TwoRowTextButton showArrow={false} upperText={"Mängutüübid"} lowerText={Object.keys(stats.gameTypes[statsPeriod]).length + " mängutüüp" + (Object.keys(stats.gameTypes[statsPeriod]).length == 1 ? "" : "i")} />

                        <div className="types" style={{margin:"0 8px"}}>
                             {Object.keys(stats.gameTypes[statsPeriod]).length == 0 && <p style={{color:"var(--grey-color)"}}>Sellel ajaperioodil ei ole ühtegi mängu mängitud</p> }
                             {Object.keys(stats.gameTypes[statsPeriod]).map(e=> <p key={e}>{gameName(decodeURIComponent(e))} <span style={{color:"var(--grey-color)"}}>| {stats.gameTypes[statsPeriod][e]}%</span></p>)}
                        </div>
                    </div>
                </div>

                <div>
                    <div className="section">
                        <TwoRowTextButton showArrow={false} upperText={"Kalender"} lowerText={"Aktiivsed päevad"} />

                        <div className="calendar">
                            <div className="header">
                                <button id="prev">&#8592;</button>
                                <div id="monthYear"></div>
                                <button id="next">&#8594;</button>
                            </div>
                            <div className="days" id="days"></div>
                        </div>

                    </div>

                    <div style={{display:"grid", gridTemplateColumns:"repeat(2, 1fr)", gap:"16px"}}>
                        <StreakWidget streak={stats.streak} active={stats.streak_active} />
                        <StatisticsTile stat={stats.points} compactNumber={true} label={"Punkti kokku"} icon={"functions"} />
                    </div>
                </div>
            </div>
        </Layout>
    </>;
}