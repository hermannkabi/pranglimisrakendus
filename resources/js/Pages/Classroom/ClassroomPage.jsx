export default function ClassroomPage({leaderboard, teacher, users}){
    console.log(leaderboard);
    console.log(users);
    return <h1>{teacher.eesnimi} {teacher.perenimi}</h1>
}