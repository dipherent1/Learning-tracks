import { getPostData } from "@/lib/posts";

type Props = {
    params: {
        id: string
    }
}


export default async function Post ({ params }: Props){
    const postData = await getPostData(params.id)

    return (
        <article>
            <h1>{ postData.title }</h1>
            <div>{ postData.date }</div>
            <br />
            <div dangerouslySetInnerHTML={{ __html: postData.contentHtml }} />
        </article>
    )
}