export default function SizedBox(props){
    return <div {...props} style={{height:(props.height ?? "8px"), width:(props.width ?? "0")}}></div>
}