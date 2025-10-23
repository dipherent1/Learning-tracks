// src/app/page.tsx
import Link from 'next/link'
import { getSortedPostsData } from '@/lib/posts'
import AuthButton from '@/components/AuthButton'

export default function Home() {
  const allPostsData = getSortedPostsData()

  return (
    <main>
      <h1>My Professional Blog</h1>
      <AuthButton />

      <section>
        <h2>Blog Posts</h2>
        <ul>
          {allPostsData.map(({ id, date, title }) => (
            <li key={id}>
              <Link href={`/posts/${id}`}>{title}</Link>
              <br />
              <small>{date}</small>
            </li>
          ))}
        </ul>
      </section>
    </main>
  )
}